<?php
namespace Faker\Components\Engine\Common\Visitor;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Compiler\Graph\DirectedGraph;
use Faker\Components\Engine\Common\Composite\CompositeException;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\PathBuilder;
use Faker\Components\Engine\Common\Composite\SchemaNode;
use Faker\Components\Engine\Common\Composite\TableNode;
use Faker\Components\Engine\Common\Composite\ColumnNode;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Composite\CompositeFinder;
use Faker\Components\Engine\Common\Composite\FormatterNode;
use Faker\Components\Engine\Common\Composite\DatasourceNode;
use Faker\Components\Engine\Common\Type\FromSource;


/*
 *
 * Will gather all datasources
 * 
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.5
 */
class DSourceGatherVisitor extends BasicVisitor
{
    
    /**
      *  @var DirectedGraph
      */
    protected $graph = null;
    
    /**
     * @var PathBuilder
    */
    protected $pathBuilder;
    
    /**
      *  Class Constructor
      *
      *  @return void
      *  @access public 
      */
    public function __construct(DirectedGraph $graph, PathBuilder $pathBuilder)
    {
        $this->graph       = $graph;
        $this->pathBuilder = $pathBuilder;
    }
    
    
    //------------------------------------------------------------------
    # Visitor Methods
    
    public function visitGeneratorInjector(CompositeInterface $node)
    {
        return null;
    }
    
    public function visitLocaleInjector(CompositeInterface $node)
    {
        return null;
    }
    
    public function visitDBALGatherer(CompositeInterface $node)
    {
        return null;
    }
    
    public function visitDirectedGraphBuilder(CompositeInterface $composite)
    {
        return null;
    }
    
    
    public function visitDatasourceGatherer(CompositeInterface $node)
    {
        $builder     = $this->pathBuilder;
        $parentNode  = $composite->getParent();
                
        if($composite instanceof SchemaNode) {
            $this->graph->setRoot($composite);    
        
        } elseif ($composite instanceof DatasourceNode) {
            # have a datasource related to schema it contained in.
            $finder      = new CompositeFinder();
            $parentSchema = $finder
                            ->set($composite)
                            ->parentSchema()
                            ->get();
                            
            $this->graph->connect($builder->buildPath($composite),$composite,$builder->buildPath($parentSchema),$parentSchema);
       
        } elseif($composite instanceof TypeNode && $composite->getType() instanceof FromSource) {
           # if have Type node with internal FromSource.
           # were not relating this 
           
           $finder      = new CompositeFinder();
           $parentTable = $finder
                            ->set($composite)
                            ->parentTable()
                            ->get();
           
           $parentColumn = $finder
                            ->set($composite)
                            ->parentColumn()
                            ->get();
           
           
           $fkTableName  = $composite->getOption('foreignTable');
           $fkColumnName = $composite->getOption('foreignColumn');
           $fkTable      = $finder
                            ->set($composite)
                            ->table($fkTableName)
                            ->get();
           
           # Does the foreign table exist
           if($fkTable === null) {
                throw new CompositeException($composite,sprintf('The Foreign Table %s does not exist',$fkTableName));
           }
            
           # match the column
           $fkColumn    = $finder
                            ->set($composite)
                            ->table($fkTableName)
                            ->column($fkColumnName)
                            ->get();
           
           if($fkColumn === null) {
                return new CompositeException($composite,sprintf('The Foreign Column %s.%s does not exist',$fkTableName,$fkColumnName));
           }
           
            # a Column could be related to many others for examaple as a composite primary key so
            # the ResultCache can't be attached to a column but instead to the ForeignKeyNode child of the column
            $this->graph->connect($builder->buildPath($composite),$composite,$builder->buildPath($fkColumn),$fkColumn);
            
            # connect the two columns for easy lookup for Circular Reference checks
            $this->graph->connect($builder->buildPath($parentColumn),$parentColumn,$builder->buildPath($fkColumn),$fkColumn);
                            
            # tables are now related connect them for easy lookup for Circular Reference checks
            $this->graph->connect($builder->buildPath($parentTable),$parentTable,$builder->buildPath($fkTable),$fkTable);
           
        }
        
    }
    
    
    //------------------------------------------------------------------
    
    /**
      *  Will fetch the results map
      *
      *  @access public
      *  @return Faker\Components\Engine\Original\Compiler\Graph\DirectedGraph
      */
    public function getResult()
    {
        return $this->graph;
    }
    
    /**
     * Reset the Directed Graph
     
     * @access public
     * @return void
     *
    */
    public function reset()
    {
        $this->graph->clear();
    }
    
   
}
/* End of File */
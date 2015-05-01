<?php
namespace Faker\Components\Engine\Common\Visitor;

use Faker\Components\Engine\EngineException;
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
use Faker\Components\Engine\Common\Type\BindDataInterface;


/*
 *
 * Will gather all datasources
 * 
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.5
 */
class DSourceInjectorVisitor extends BasicVisitor
{
    /**
     * @var PathBuilder
    */
    protected $pathBuilder;
    
    /**
     * @var array of data sources
     */ 
    protected $sources;
    
    /**
      *  Class Constructor
      *
      *  @return void
      *  @access public 
      */
    public function __construct(PathBuilder $pathBuilder)
    {
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
    
    
    public function visitDatasourceInjector(CompositeInterface $composite)
    {
        if($this->sources === null) {
           
           $this->sources = array();
           $finder        = new CompositeFinder();
           $root          = $finder->set($composite)->parentSchema()->get();
           
           foreach($root->getChildren() as $child) {
               if($child instanceof DatasourceNode) {
                   $sourceObj = $child->getDatasource();
                   $sname =  $sourceObj->getOption('id');
                   
                   if(true === empty($sname)) {
                       throw new EngineException('Have a datasource without and id/name');
                   }
                   
                   $this->sources[$sname] = $child; 
               }
               
           }
           
        }
        
        $builder     = $this->pathBuilder;
        $sources     = $this->sources;
        $parentNode  = $composite->getParent();
        
        if($composite instanceof TypeNode && $composite->getType() instanceof BindDataInterface) {
            
            # verify that the source exists
            $sourceName = $composite->getType()->getOption('source');
            
            if(false === isset($this->sources[$sourceName])) {
                throw new EngineException('The fromSource at '.$builder->buildPath($composite).' Node is looking for a datasouce with name of '.$sourceName . ' that can not be found');
            }
            
            $source = $this->sources[$sourceName];
            
            $finder      = new CompositeFinder();
            $parentTable = $finder
                            ->set($composite)
                            ->parentTable()
                            ->get();            
            
            # add soruce to the parent table so that each row will cause one row to be fetched and cleanup after use.
            $parentTable->addChild($source);
            
            # add source to the type node who's job to fetch data and bind it to the FormSource Interval type
            $composite->addChild($source);
            
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
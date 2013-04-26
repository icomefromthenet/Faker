<?php
namespace Faker\Components\Engine\Original\Visitor;


use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Composite\CompositeException;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Compiler\Graph\DirectedGraph;

use Faker\Components\Engine\Common\Composite\SchemaNode;
use Faker\Components\Engine\Common\Composite\TableNode;
use Faker\Components\Engine\Common\Composite\ColumnNode;
use Faker\Components\Engine\Common\Composite\ForeignKeyNode;
use Faker\Components\Engine\Common\Composite\CompositeFinder;
use Faker\Components\Engine\Common\Type\Type as BaseType;


/*
 * class DirectedGraphVisitor
 *
 * Will gather all Table relationships
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.3
 */
class DirectedGraphVisitor extends BaseVisitor
{
    
    /**
      *  @var DirectedGraph
      */
    protected $graph = null;
    
    /**
      *  Class Constructor
      *
      *  @return void
      *  @access public 
      */
    public function __construct(DirectedGraph $graph)
    {
        $this->graph = $graph;
    }
    
    
    //------------------------------------------------------------------
    # Visitor Methods
    
    public function visitLocaleInjector(CompositeInterface $node)
    {
        return null;
    }
    
    public function visitDBALGatherer(CompositeInterface $node)
    {
        return null;
    }
    
    public function visitDirectedGraph(CompositeInterface $composite)
    {
        if($composite instanceof SchemaNode) {
            $this->graph->setRoot($composite);    
        
        } elseif($composite instanceof TableNode) {
            # if we have a table connect to schema
            $this->graph->connect($composite->getId(),$composite,$composite->getParent()->getId(),$composite->getParent());
            
        } elseif($composite instanceof ColumnNode) {
            # if instance of column connect to table
            $this->graph->connect($composite->getId(),$composite,$composite->getParent()->getId(),$composite->getParent());            
                
        } elseif($composite instanceof ForeignKeyNode) {
            # if have a fk connect two columns and tables as well as the FKNode to FKColumn 
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
            $this->graph->connect($composite->getId(),$composite,$fkColumn->getId(),$fkColumn);
            
            # connect the two columns for easy lookup for Circular Reference checks
            $this->graph->connect($parentColumn->getId(),$parentColumn,$fkColumn->getId(),$fkColumn);
                            
            # tables are now related connect them for easy lookup for Circular Reference checks
            $this->graph->connect($parentTable->getId(),$parentTable,$fkTable->getId(),$fkTable);
           
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
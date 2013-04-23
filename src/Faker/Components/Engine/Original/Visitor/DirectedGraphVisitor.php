<?php
namespace Faker\Components\Engine\Common\Visitor;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Compiler\Graph\DirectedGraph;
use Faker\Components\Engine\Common\Composite\SchemaNode;
use Faker\Components\Engine\Common\Composite\TableNode;
use Faker\Components\Engine\Common\Composite\ColumnNode;
use Faker\Components\Engine\Common\Composite\ForeignKeyNode;
use Faker\Components\Engine\Common\Composite\SelectorNode;
use Faker\Components\Engine\Common\Composite\CompositeFinder;
use Faker\Components\Engine\Common\Type\Type as BaseType;
    

/*
 * DirectedGraphVisitor will build a directed graph from a composite
 *
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.3
 */
class DirectedGraphVisitor extends BaseVisitor
{
    
    /**
      *  @var DirectedGraph
      */
    protected $graph = array();
    
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
   
    
    public function visitDirectedGraphBuilder(CompositeInterface $composite)
    {
        if($composite instanceof SchemaNode) {
            # schema add to the graph    
            $this->graph->setSchema($composite);
        
        } elseif($composite instanceof TableNode) {
            # if we have a table connect to schema
            $this->graph->connect($composite->getId(),$composite,$composite->getParent()->getId(),$composite->getParent());
            
        } elseif($composite instanceof ColumnNode) {
            # if instance of column connect to table
            $this->graph->connect($composite->getId(),$composite,$composite->getParent()->getId(),$composite->getParent());            
                
        } elseif($composite instanceof ForeignKeyNode) {
            # if have a fk connect two columns together and connect the fk with parent element          
            #$this->graph->connect($composite->getId(),$composite,$composite->getParent()->getId(),$composite->getParent());            
        
           $finder = new CompositeFinder();
        
           $parent_table = $finder->set($composite)->parentTable()->get(); 
        
           # Find the column referenced by the ForeignKey and connect it.
           # If the reference is bad no exception is thrown, need to run compiler ReferenceCheckPass.
           $schema  = $this->graph->getSchema();
           foreach($schema->getChildren() as $table) {
                if($table->getOption('name') === $composite->getOption('foreignTable')) {
                    # scan the columns
                    foreach($table->getChildren() as $column) {
                        if($column->getOption('name') === $composite->getOption('foreignColumn')) {
                            
                            # connect the fk type to the referenced column
                            $this->graph->connect($composite->getId(),$composite,$column->getId(),$column);
                            
                            # tables are now related connect them
                            $this->graph->connect($parent_table->getId(),$parent_table,$table->getId(),$table);
                            
                            break;
                        }
                    }
                    break;
                }
           }
           
        } elseif ($composite instanceof SelectorInterface) {
           # process selectors add to parent 
           $this->graph->connect($composite->getId(),$composite,$composite->getParent()->getId(),$composite->getParent());            
           
        } elseif ($composite instanceof BaseType) {
           # process add datatype to parent could be column or selector
           $this->graph->connect($composite->getId(),$composite,$composite->getParent()->getId(),$composite->getParent());            
           
        } else {
            # could be writer but we will ignore those
        }
        
        
    }
    
    
    public function reset()
    {
        throw new EngineException('This Visitor can not be reset');
    }
    
    public function getResult()
    {
        return $this->graph;
    }
    
   
}
/* End of File */
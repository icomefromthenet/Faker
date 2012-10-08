<?php
namespace Faker\Components\Faker\Visitor;

use Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Compiler\Graph\DirectedGraph,
    Faker\Components\Faker\Composite\Schema,
    Faker\Components\Faker\Composite\Table,
    Faker\Components\Faker\Composite\Column,
    Faker\Components\Faker\Composite\ForeignKey,
    Faker\Components\Faker\Composite\SelectorInterface,
    Faker\Components\Faker\Composite\CompositeFinder,
    Faker\Components\Faker\Type\Type as BaseType,
    Faker\Components\Faker\BaseNode;

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
    
    public function visitCacheInjector(CompositeInterface $composite)
    {
        throw new FakerException('Not Implemented');
    }
    
    public function visitRefCheck(CompositeInterface $composite)
    {
        throw new FakerException('Not implemented');
    }
    
    public function visitGeneratorInjector(CompositeInterface $composite)
    {
         throw new FakerException('Not implemented');
    }
    
    public function visitLocale(CompositeInterface $composite)
    {
        throw new FakerException('Not Implemented');
    }
    
    public function visitDirectedGraph(CompositeInterface $composite)
    {
        if($composite instanceof Schema) {
            # schema add to the graph    
            $this->graph->setSchema($composite);
        
        } elseif($composite instanceof Table) {
            # if we have a table connect to schema
            $this->graph->connect($composite->getId(),$composite,$composite->getParent()->getId(),$composite->getParent());
            
        } elseif($composite instanceof Column) {
            # if instance of column connect to table
            $this->graph->connect($composite->getId(),$composite,$composite->getParent()->getId(),$composite->getParent());            
                
        } elseif($composite instanceof ForeignKey) {
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
    
    
    public function visitMapBuilder(CompositeInterface $composite)
    {
        throw new FakerException('Not Implemented');
    }
    
    //------------------------------------------------------------------
    
    /**
      *  Will fetch the results map
      *
      *  @access public
      *  @return Faker\Components\Faker\Compiler\Graph\DirectedGraph
      */
    public function getDirectedGraph()
    {
        return $this->graph;
    }
    
   
}
/* End of File */
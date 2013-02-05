<?php
namespace Faker\Components\Engine\Original\Visitor;

use Faker\Components\Engine\Original\Composite\CompositeInterface,
    Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Original\Composite\Table,
    Faker\Components\Engine\Original\Composite\Column,
    Faker\Components\Engine\Original\Composite\ForeignKey;

/*
 * class MapBuilderVisitor
 *
 * Will gather all column relationships
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
class MapBuilderVisitor extends BaseVisitor
{
    
    /**
      *  @var Relationships
      */
    protected $results = array();
    
    /**
      *  Class Constructor
      *
      *  @return void
      *  @access public 
      */
    public function __construct(Relationships $rel)
    {
        $this->results = $rel;
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
        throw new FakerException('Not Implemented');
    }
    
    public function visitMapBuilder(CompositeInterface $composite)
    {
        
        # scan for fk to mine for indexes
        if($composite instanceof ForeignKey) {
           
           # iterate up the chain till get a column or excpetion if first node in composite (schema)
           $node = $composite;
           do
           {
                $node = $node->getParent();
           }
           while(!$node instanceof Column);
           $column = $node;
           
           $node = $composite; 
           do
           {
                $node = $node->getParent();
           }
           while(!$node instanceof Table);
           $table  = $node;
     
           
           $this->results->add(new Relationship(
                                         new Relation($table->getOption('name'),$column->getOption('name'),$composite->getOption('name')),
                                         new Relation($composite->getOption('foreignTable'),$composite->getOption('foreignColumn'))
                                         )
                        );
        }
    }
    
    //------------------------------------------------------------------
    
    /**
      *  Will fetch the results map
      *
      *  @access public
      *  @return mixed[] the map results
      */
    public function getMap()
    {
        return $this->results;
    }

}
/* End of File */
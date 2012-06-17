<?php
namespace Faker\Components\Faker\Visitor;

use Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Composite\Table,
    Faker\Components\Faker\Composite\Column,
    Faker\Components\Faker\Composite\ForeignKey;

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
    
    public function visitMapBuilder(CompositeInterface $composite)
    {
        
        
        
        # scan for fk to mine for indexes
        if($composite instanceof ForeignKey) {
           
           # fetch foreign details 
           $foreign_table   = $composite->getOption('foreignTable');
           $foreign_column  = $composite->getOption('foreignColumn');
           
           # fetch the fk address details
           $column = $composite->getParent();
           $table  = $column->getParent();
           
           $column_name = $column->getId();
           $table_name  = $table->getId();
           
           $this->results->add(new Relationship(
                                         new Relation($table_name,$column_name,$composite->getId()),
                                         new Relation($foreign_table,$foreign_column)
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
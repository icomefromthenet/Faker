<?php
namespace Faker\Components\Faker\Visitor;

/*
 * class Relationships
 * 
 * A collection of relationship.
 *  
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
class Relationships implements \IteratorAggregate , \Countable
{
    /**
      *  @var array of relationship objects 
      */
    protected $relationships;
    
    /*
     * __construct()
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->relationships = array();
    }
    
    
    public function add(Relationship $rel)
    {
        $this->relationships[] = $rel;        
    }
    
    
    public function getLocalRelations()
    {
        $local = array();
        
        foreach($this->relationships as $relationship) {
            $local[] = $relationship->getLocal();
        }
        
        return $local;
    }
    
    
    public function getForeignRelations()
    {
        $foreign = array();
        
        foreach($this->relationships as $relationship) {
            $foreign[] = $relationship->getForeign();
        }
        
        return $foreign;
    }
    
    //------------------------------------------------------------------
    # IteratorAggregate Interface
    
    public function getIterator()
    {
        return new \ArrayIterator($this->relationships);
    }
    
    //------------------------------------------------------------------


    public function count()
    {
        return count($this->relationships);
    }

}
/* End of File */
<?php
namespace Faker\Components\Engine\Original\Visitor;

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
    
    
    public function getForeignRelationsByTable($table)
    {
        $foreign = array();
        
        foreach($this->relationships as $relationship) {
            if($relationship->getForeign()->getTable() === $table) {
                $foreign[] = $relationship->getForeign();    
            }
            
        }
        
        return $foreign;
        
    }
    
    public function getLocalRelationsByTable($table)
    {
         $local = array();
        
        foreach($this->relationships as $relationship) {
            if($relationship->getLocal()->getTable() === $table) {
                $local[] = $relationship->getLocal();
            }
        }
        
        return $local;
    }
    
    
    public function filterByLocalTable($table)
    {
        $local = array();
        
        foreach($this->relationships as $relationship) {
            if($relationship->getLocal()->getTable() === $table) {
                $local[] = $relationship;
            }
        }
        
        return $local;
    }
        
    public function filterByForeignTable($table)
    {
        $foreign = array();
        
        foreach($this->relationships as $relationship) {
            if($relationship->getForeign()->getTable() === $table) {
                $foreign[] = $relationship;    
            }
            
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

    
    //------------------------------------------------------------------
}
/* End of File */
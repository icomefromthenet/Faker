<?php
namespace Faker\Components\Faker\Visitor;

/*
 * class Relationship
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
class Relationship
{
    /**
      *  Local side of the relationship 
      */
    protected $local;
    
    /**
      *  Foreign side of the relation (key originates) 
      */
    protected $foreign;
    
    
    /*
     * __construct()
     *
     * @param Relation $local   (local reference)
     * @param Relation $foreign (reference to where they key originates)
     * @return void
     * @access public
     */
    
    public function __construct(Relation $local, Relation $foreign)
    {
        $this->local   = $local;
        $this->foreign = $foreign;
    }
    
    /**
      *  Fetch the local relation
      *
      *  @return Relation
      *  @access public
      */
    public function getLocal()
    {
        return $this->local;
    }
    
    /**
      *   Fetch the Foreign relation (key originates)
      *
      *   @return Relation
      *   @access public
      */
    public function getForeign()
    {
        return $this->foreign;
    }
    
}
/* End of File */
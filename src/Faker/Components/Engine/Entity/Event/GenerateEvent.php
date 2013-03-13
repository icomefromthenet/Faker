<?php
namespace Faker\Components\Engine\Entity\Event;

use Symfony\Component\EventDispatcher\Event;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Entity\GenericEntity;

/**
  *  Event class used in entity composite 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class GenerateEvent extends Event
{
    
    protected $node;
    
    
    protected $result;
    
    
    protected $entity;
    
    /**
      *  Class Constructor 
      */
    public function __construct(CompositeInterface $node, GenericEntity $entity = null, $result = null)
    {
        $this->node   = $node;
        $this->entity = $entity;
        $this->result = $result;
    }
    
    /**
      *  Returns the node that generated the event
      *
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface
      */    
    public function getNode()
    {
        return $this->node;
    }
    
    /**
      *  Returns the GenericEntity (Past Generated Results)
      *
      *  @return Faker\Components\Engine\Entity\GenericEntity
      */
    public function getEntity()
    {
        return $this->entity;        
    }
    
    /**
      *  Return the result generated
      *
      *  @return mixed the result 
      */
    public function getResult()
    {
        return $this->result;
    }
        
}
/* End of File */
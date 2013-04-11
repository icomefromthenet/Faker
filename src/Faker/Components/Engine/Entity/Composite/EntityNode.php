<?php
namespace Faker\Components\Engine\Entity\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Entity\Event\GenerateEvent;
use Faker\Components\Engine\Entity\GenericEntity;
use Faker\Components\Engine\EngineException;

class EntityNode implements CompositeInterface
{
    
    protected $id; 
    
    protected $children = array();
    
    protected $event;
    
    /**
      *  Class Constructor
      *
      *  @param string $id the nodes id
      */    
    public function __construct($id,EventDispatcherInterface $event)
    {
        $this->id       = $id;
        $this->children = array();
        $this->event    = $event;
    }
    
    //------------------------------------------------------------------
    # GeneratorInterface
    
      
    /**
      *  Get Event Dispatcher
      *
      *  @return Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */ 
    public function getEventDispatcher()
    {
        return $this->event;
    }
    
    
    
    /**
      *  Generate a value
      *
      *  @param integer $rows the current row number
      *  @param mixed $array list of values generated in context
      */
    public function generate($rows,$values = array())
    {
        $entity  = new GenericEntity();
        
        $this->getEventDispatcher()->dispatch(FormatEvents::onRowStart,new GenerateEvent($this,$entity,null));
        
        foreach($this->getChildren() as $child) {
            $child->generate($rows,$entity);
        }
        
        $this->getEventDispatcher()->dispatch(FormatEvents::onRowEnd,new GenerateEvent($this,$entity,null));
                
        return $entity;
    }
    
    
    /**
      *  Will Merge options with config definition and pass judgement
      *
      *  @access public
      *  @return boolean true if passed
      */
    public function validate()
    {
        foreach($this->children as $child) {
            $child->validate();
        }
        
        return true;
    }
    
    //------------------------------------------------------------------
    # Composite Interface
    
    /**
      *  Fetches the parent in this type composite
      *
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface
      *  @access public
      */
    public function getParent()
    {
        return null;
    }

    /**
      *  Sets the parent of this type composite
      *
      *  @access public
      *  @param Faker\Components\Engine\Common\Composite\CompositeInterface $parent;
      */
    public function setParent(CompositeInterface $parent)
    {
        throw new EngineException('not implemented');
    }
    
    
    /**
      *   Fetches the children of this type composite
      *
      *   @access public
      *   @return Faker\Components\Engine\Common\Composite\CompositeInterface[] 
      */
    public function getChildren()
    {
        return $this->children;
    }
    
    
    /**
      *  Add's a child to this type composite
      *
      *  @param Faker\Components\Engine\Common\Composite\CompositeInterface $child
      */
    public function addChild(CompositeInterface $child)
    {
        $this->children[] = $child;
    }
    
    
    /**
      *  Return the nodes id
      *
      *  @access public
      *  @return string the nodes id
      */
    public function getId()
    {
        return $this->id;
    }
    
        
}
/* End of File */
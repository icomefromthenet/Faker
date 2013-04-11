<?php
namespace Faker\Components\Engine\Entity\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Entity\Event\GenerateEvent;
use Faker\Components\Engine\Entity\GenericEntity;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Type\TypeInterface;


/**
  *  Field Node for Generator
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class FieldNode implements CompositeInterface
{
    
    protected $id; 
    
    protected $children = array();
    
    protected $event;
    
    protected $parent;
    
    /**
      *  Class Constructor
      *
      *  @param string $id the nodes id
      *  @param Symfony\Component\EventDispatcher\EventDispatcherInterface $event the event bus
      */    
    public function __construct($id,EventDispatcherInterface $event)
    {
        $this->id       = $id;
        $this->parent   = null;
        $this->children = array();
        $this->event    = $event;
    }
    
    //------------------------------------------------------------------
    # GeneratorInterface
    
    
    /**
      *  Generate a value
      *
      *  @param integer $rows the current row number
      *  @param mixed $array list of values generated in context
      */
    public function generate($rows,$values = array())
    {
        $entity  = new GenericEntity();
        $result  = null;
        $field   = $this->getId();
        
        $this->getEventDispatcher()->dispatch(FormatEvents::onColumnStart,new GenerateEvent($this,$entity,null));
        
        # bind result to the generic entity
        
        if(count($this->children) > 1) {
            
            foreach($this->children as $child) {
               $entity->$field .= $child->generate($rows,$values);    
            }
            
        } else {
             $entity->$field = $this->children[0]->generate($rows,$values);    
        }
        
        
        $this->getEventDispatcher()->dispatch(FormatEvents::onColumnEnd,new GenerateEvent($this,$entity,null));
                
        return $entity;
    }
    
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
      *  Will Merge options with config definition and pass judgement
      *
      *  @access public
      *  @return boolean true if passed
      */
    public function validate()
    {
       return $this->type->validate();
    }
    
    //------------------------------------------------------------------
    # CompositeInterface
    
    /**
      *  Fetches the parent in this type composite
      *
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface
      *  @access public
      */
    public function getParent()
    {
        return $this->parent;
    }

    /**
      *  Sets the parent of this type composite
      *
      *  @access public
      *  @param Faker\Components\Engine\Common\Composite\CompositeInterface $parent;
      */
    public function setParent(CompositeInterface $parent)
    {
        $this->parent = $parent;
    }
    
    
    /**
      *   Fetches the children of this type composite
      *
      *   @access public
      *   @return Faker\Components\Engine\Common\Composite\CompositeInterface[] 
      */
    public function getChildren()
    {
        return $this->type;
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
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
    
    public function generate($rows,$values = array())
    {
        $result  = null;
        $field   = $this->getId();
        
        $this->getEventDispatcher()->dispatch(FormatEvents::onColumnStart,new GenerateEvent($this,$values,null));
        
        if(count($this->children) > 1) {
            foreach($this->children as $child) {
                $result .= $child->generate($rows,$values);
            }    
        } else {
            $result = $this->children[0]->generate($rows,$values);
        }
        
        # bind result to the generic entity
        $values->$field  = $result;
        
        $this->getEventDispatcher()->dispatch(FormatEvents::onColumnEnd,new GenerateEvent($this,$values,null));
                
        return $values;
    }
    
    public function getEventDispatcher()
    {
        return $this->event;
    }
    
    public function setEventDispatcher(EventDispatcherInterface $event)
    {
        $this->event = $event;
    }
    
    public function validate()
    {
       foreach($this->children as $child) {
            $child->validate();
        }
        
        return true;
    }
    
    //------------------------------------------------------------------
    # CompositeInterface
    
    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(CompositeInterface $parent)
    {
        $this->parent = $parent;
    }
    
    public function getChildren()
    {
        return $this->children;
    }
    
    public function addChild(CompositeInterface $child)
    {
        $this->children[] = $child;
    }
    
    public function getId()
    {
        return $this->id;
    }
}
/* End of File */
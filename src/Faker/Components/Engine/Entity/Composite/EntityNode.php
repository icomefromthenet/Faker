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
    
    public function getEventDispatcher()
    {
        return $this->event;
    }
    
    public function setEventDispatcher(EventDispatcherInterface $event)
    {
        $this->event = $event;
    }

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
    
    
    public function validate()
    {
        foreach($this->children as $child) {
            $child->validate();
        }
        
        return true;
    }
    
    //------------------------------------------------------------------
    # Composite Interface
    
    public function getParent()
    {
        return null;
    }

    public function setParent(CompositeInterface $parent)
    {
        throw new EngineException('not implemented');
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
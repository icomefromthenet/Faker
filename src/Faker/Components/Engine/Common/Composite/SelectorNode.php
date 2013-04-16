<?php
namespace Faker\Components\Engine\Common\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Type\TypeInterface;
use Faker\Components\Engine\Common\Type\Type;
use Faker\Components\Engine\Common\Utilities;
use Faker\Locale\LocaleInterface;

/**
  *  Node to contain selectors
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class SelectorNode implements CompositeInterface
{
    
    /**
      *  @var Faker\Components\Engine\Common\Selector\TypeInterface 
      */
    protected $selector;
    
    /**
      *  @var Faker\Components\Engine\Common\Selector\TypeInterface 
      */
    protected $parent;
    
    /**
      *  @var array[TypeInterface]
      */
    protected $children;
    
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */
    protected $event;
    
    /**
      *  @var the nodes id 
      */
    protected $id;
    
    /**
      *  Class Constructor
      *
      *  
      */
    public function __construct($id, EventDispatcherInterface $event, TypeInterface $type)
    {
        $this->id       = $id;
        $this->selector = $type;
        $this->children = array();
        $this->parent   = null;
        $this->event    = $event;
        
    }
    
    
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
        $index = $this->selector->generate($rows,$values);
    
        return $this->children[$index-1]->generate($rows,$values);
    }
    
    
    public function validate()
    {
        $this->selector->validate();
        
        foreach($this->getChildren() as $child) {
          $child->validate(); 
        }
        
        return true;        
    }
    
}
/* End of File */
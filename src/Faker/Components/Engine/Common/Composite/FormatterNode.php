<?php
namespace Faker\Components\Engine\Common\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Formatter\BaseFormatter;
use Faker\Components\Engine\Common\Type\TypeInterface;
use Faker\Components\Engine\Common\Type\Type;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;
use Faker\Locale\LocaleInterface;

/**
  *  Node to contain formatters
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class FormatterNode implements CompositeInterface, VisitorInterface
{
    
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
      *  @var Faker\Components\Engine\Common\Formatter\BaseFormatter 
      */
    protected $formatter;
    
    /**
      *  Class Constructor
      *
      *  
      */
    public function __construct($id, EventDispatcherInterface $event,BaseFormatter $formatter)
    {
        $this->id        = $id;
        $this->children  = array();
        $this->parent    = null;
        $this->event     = $event;
        $this->formatter = $formatter;
        
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
        $child->setParent($this);
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
    
    /**
      *  Fetch the internal formatter
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Formatter\BaseFormatter 
      */
    public function getInternal()
    {
        return $this->formatter;
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
    
    
    public function validate()
    {
        foreach($this->getChildren() as $child) {
          $child->validate(); 
        }
        
        return true;        
    }
    
    
     //------------------------------------------------------------------
    # VisitorInterface
    
     public function acceptVisitor(BasicVisitor $visitor)
     {
        $children = $this->getChildren();
        
        foreach($children as $child) {
            if($child instanceof VisitorInterface) {
                $child->acceptVisitor($visitor);                    
            }
        }
        
        return $visitor;
     }
    
}
/* End of File */
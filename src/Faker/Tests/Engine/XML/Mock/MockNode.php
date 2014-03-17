<?php
namespace Faker\Tests\Engine\XML\Mock;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\OptionInterface;
use Faker\Components\Engine\Common\Composite\CompositeException;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\GeneratorInterface;
use Faker\Components\Engine\Common\Composite\SerializationInterface;
use Faker\Components\Engine\Common\Composite\VisitorInterface;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;
use Faker\Components\Engine\Common\GeneratorCache;

/**
  *  Mock Node
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class MockNode implements CompositeInterface, VisitorInterface, GeneratorInterface, SerializationInterface, OptionInterface
{
    
    
    protected $options;
    
    
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
    public function __construct($id, EventDispatcherInterface $event)
    {
        $this->id       = $id;
        $this->children = array();
        $this->parent   = null;
        $this->event    = $event;
        
    }
    
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
	$child->setParent($this);
    }
    
    
    public function clearChildren()
    {
	unset($this->children);
	$this->children = array();
    }
    
    
    public function getId()
    {
        return $this->id;
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
        foreach($this->getChildren() as $child) {
          $child->validate(); 
        }
        
        return true;        
    }
    
    public function generate($rows,&$values = array(),$last = array())
    {
	return null;
    }
    
    
    public function setResultCache(GeneratorCache $cache)
    {
	
    }
    
    public function getResultCache()
    {
	
    }
    
    
    public function acceptVisitor(BasicVisitor $visitor)
    {
	
    }
    
    
     //-------------------------------------------------------
    # SerializationInterface
    
    public function toXml()
    {
        
    }
    
    
     //-------------------------------------------------------
    # OptionsInterface
    
    public function setOption($name,$value)
    {
        $this->options[$name]= $value;
    }
    
    public function getOption($name)
    {
        return $this->options[$name];
    }
    
    public function hasOption($name)
    {
         return isset($this->options[$name]);
    }
    
    public function getConfigTreeBuilder()
    {
        
    }
    
}
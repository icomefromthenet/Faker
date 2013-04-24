<?php
namespace Faker\Tests\Engine\DB\Mock;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Composite\CompositeException;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\GeneratorInterface;
use Faker\Components\Engine\Common\Composite\VisitorInterface;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;
use Faker\Components\Engine\Common\GeneratorCache;

/**
  *  Mock Root Node
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class MockRootNode implements CompositeInterface, VisitorInterface, GeneratorInterface
{
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
        return null;
    }

    public function setParent(CompositeInterface $parent)
    {
        throw new CompositeException($this,'not implemented',0);
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
    
    
    public function generate($rows,&$values = array())
    {
	
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
    
}
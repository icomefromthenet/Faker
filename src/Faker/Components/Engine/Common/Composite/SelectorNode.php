<?php
namespace Faker\Components\Engine\Common\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\Type\Type;
use Faker\Components\Engine\Common\Type\TypeInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;
use Faker\Components\Engine\Common\GeneratorCache;


/**
  *  Node to contain selectors
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class SelectorNode implements CompositeInterface, GeneratorInterface, VisitorInterface
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
      *  @var GeneratorCache 
      */
    protected $resultCache;
    
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
        $this->children = null;
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
        $this->selector->validate();
        
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
  
    
    //------------------------------------------------------------------
    # GeneratorInterface
   
    
    public function generate($rows,&$values = array())
    {
        $index = $this->selector->generate($rows,$values);
    
        return $this->children[$index-1]->generate($rows,$values);
    }
    
      /**
      *  Sets the Generator Result Cache
      *
      *  @access public
      *  @param GeneratorCache $cache
      */
    public function setResultCache(GeneratorCache $cache)
    {
        $this->resultCache  = $cache;
    }
    
    /**
      *  Return the Generator Result Cache
      *
      *  @access public
      *  @return GeneratorCache
      */
    public function getResultCache()
    {
        return $this->resultCache;
    }
    
    //------------------------------------------------------------------
    # Custom
    
    
    /**
      *  Fetch the internal selector
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Type\TypeInterface
      */
    public function getInternal()
    {
        return $this->selector;
    }
    
    
}
/* End of File */
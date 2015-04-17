<?php
namespace Faker\Components\Engine\Common\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\Type\Type;
use Faker\Components\Engine\Common\Type\TypeInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;
use Faker\Components\Engine\Common\GeneratorCache;

/**
  *  Node to contain datatypes
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class TypeNode implements CompositeInterface, GeneratorInterface, VisitorInterface
{
    
    /**
      *  @var Faker\Components\Engine\Common\Selector\TypeInterface 
      */
    protected $type;
    
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
        $this->type     = $type;
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
        return array();
    }
    
    public function clearChildren()
    {
        $this->children = null;
        $this->children = array();
    }
    
    /**
      *  Add's a child to this type composite
      *
      *  @param Faker\Components\Engine\Common\Composite\CompositeInterface $child
      */
    public function addChild(CompositeInterface $child)
    {
        throw new EngineException('TypeNode can not have children');
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
        $this->type->validate();
        
        return true;        
    }
    
    //------------------------------------------------------------------
    # GeneratorInterface
    
    public function generate($rows,&$values = array(),$last = array())
    {
        return $this->type->generate($rows,$values,$last);
    }
    
    public function setResultCache(GeneratorCache $cache)
    {
        $this->resultCache = $cache;
    }
    
    public function getResultCache()
    {
        return $this->resultCache;
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
    # Custom
    
    /**
      *  Fetch the internal type
      *
      *  @access public
      *  @return TypeInterface
      */
    public function getType()
    {
        return $this->type;
    }
    
}
/* End of File */
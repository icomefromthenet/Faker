<?php
namespace Faker\Components\Engine\Common\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
  *  Basic Implementation of a Foreign Key Node this will be
  *  subclassed by the DB and XML Engines which implement GeneratorInterface and
  *  VisitorInterface.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ForeignKeyNode implements CompositeInterface
{
    
    /**
      *  @var  CompositeInterface
      */
    protected $parentNode;
    
    
    /**
      *  @var array[CompositeInterface] 
      */
    protected $children = array();
    
    /**
      *  @var  Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $event;
    
    /**
      *  @var string the nodes id 
      */
    protected $id;
    
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
    # Composite Interface
    
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
        if(empty($this->id)) {
            throw new CompositeException($this,'ForeignKey must have a name');
        }
        
        foreach($this->children as $child) {
            $child->validate();
        }
        
        return true;
    }
    
    
    public function getParent()
    {
        return $this->parentNode;
    }

    public function setParent(CompositeInterface $parent)
    {
        $this->parentNode = $parent;
    }
    
    public function getChildren()
    {
        return $this->children;
    }
    
    public function clearChildren()
    {
        $this->children = null;
        $this->children = array();
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
    
    //-------------------------------------------------------
    # Custom
    
    /*
     *  @var boolean use the cache during generation
     */
    protected $useCache = true;
    
    /**
     *  Set the property that turn cache usage off
     *
     *  @access public
     *  @return void
     *  @param boolean $bool
     *
    */
    public function setUseCache($bool)
    {
        $this->useCache = (boolean) $bool;
    }
    
    /**
     *  Fetch the property turn cache usage off
     *
     *  @access public
     *  @return boolean
     *
    */
    public function getUseCache()
    {
        return $this->useCache;
    }
    
}
/* End of File */
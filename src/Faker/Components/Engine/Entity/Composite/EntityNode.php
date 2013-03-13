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
    
    protected $locale;
    
    protected $generator;
    
    protected $id; 
    
    protected $utilities;
    
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
    # Type Interface
    
    /**
      *  Generate a value
      *
      *  @param integer $rows the current row number
      *  @param mixed $array list of values generated in context
      */
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
    
    
    /**
      *  Get the utilities property
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Utilities
      */ 
    public function getUtilities()
    {
        return $this->utilities;
    }
    
    
    /**
      *  Sets the utilities property
      *
      *  @access public
      *  @param $util Faker\Components\Engine\Common\Utilities
      */
    public function setUtilities(Utilities $util)
    {
        $this->utilities = $util;
    }
    
    
    /**
      *  Fetch the random number generator
      *
      *  @access public
      *  @return PHPStats\Generator\GeneratorInterface
      */
    public function getGenerator()
    {
        return $this->generator;
    }
    
    /**
      *  Set the random number generator
      *
      *  @access public
      *  @return PHPStats\Generator\GeneratorInterface
      */
    public function setGenerator(GeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
      *  Set the type with a locale
      *
      *  @access public
      *  @param Faker\Locale\LocaleInterface $locale
      */
    public function setLocale(LocaleInterface $locale)
    {
        $this->locale = $locale;
    }
    
    /**
      * Fetches this objects locale
      * 
      *  @return Faker\Locale\LocaleInterface
      *  @access public
      */
    public function getLocale()
    {
        return $this->locale;
    }
    
    
    /**
      *  Will Merge options with config definition and pass judgement
      *
      *  @access public
      *  @return boolean true if passed
      */
    public function validate()
    {
        foreach($this->children as $child) {
            $child->validate();
        }
        
        return true;
    }
    
    //------------------------------------------------------------------
    # Composite Interface
    
    /**
      *  Fetches the parent in this type composite
      *
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface
      *  @access public
      */
    public function getParent()
    {
        return null;
    }

    /**
      *  Sets the parent of this type composite
      *
      *  @access public
      *  @param Faker\Components\Engine\Common\Composite\CompositeInterface $parent;
      */
    public function setParent(CompositeInterface $parent)
    {
        throw new EngineException('not implemented');
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
      *  Get Event Dispatcher
      *
      *  @return Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */ 
    public function getEventDispatcher()
    {
        return $this->event;
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
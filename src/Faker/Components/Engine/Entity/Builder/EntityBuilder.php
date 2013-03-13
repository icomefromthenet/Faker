<?php
namespace Faker\Components\Engine\Entity\Builder;

use Closure;
use PHPStats\Generator\GeneratorInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Builder\ParentNodeInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Locale\LocaleInterface;


/**
  *  Used to construct generator composite to represent entities
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class EntityBuilder implements ParentNodeInterface
{

    /**
      *  @var array[NodeInterface] the builders children
      */
    protected $children;
    protected $locale;
    protected $connection;
    protected $utilities;
    protected $generator;
    
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */
    protected $event;
    
    /**
      *  @var string the entity name 
      */
    protected $name;
    
    /**
      *  Class Constructor 
      */
    public function __construct($name,EventDispatcherInterface $event)
    {
        $this->name     = $name;
        $this->event    = $event;
        $this->children = array();
    }


    //------------------------------------------------------------------
    # ParentNodeInterface
    
     /**
      *  Return the assigned parent
      *
      *  @param access
      *  @return NodeInterface
      */
    public function getParent()
    {
        return null;
    }
    
    /**
      *  Append a node to this one
      *
      *  @access public
      *  @return EntityBuilder
      *  @param NodeInterface $node
      */
    public function append(NodeInterface $node)
    {
        $node->setParent($this);
        
        $this->children[] = $node;
        
        return $this;
    }
    
    /**
      *  Return this nodes children
      *
      *  @access public
      *  @return array[NodeInterface]
      */
    public function children()
    {
        return $this->children;
    }
    
    /**
      *  Fetch the generator composite node managed by this builder node
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    public function getNode($id,CompositeInterface $parent)
    {
        
        
        
    }
    
    
    /**
    * Sets the parent node.
    *
    * @param ParentNodeInterface $parent The parent
    */
    public function setParent(NodeInterface $parent)
    {
        return $this;
    }
    
    
    /**
    * Returns the parent node.
    *
    * @return NodeInterface The builder of the parent node
    */
    public function end()
    {
        return $this;
    }
    
    
    //------------------------------------------------------------------
    # Type Defaults
    
    
    public function defaultLocale(LocaleInterface $locale)
    {
        $this->locale = $locale;
        
        return $this;
    }
    
    
    public function defaultUtility(Utilities $util)
    {
        $this->utilities = $util;
        
        return $this;
    }
    
    
    public function defaultGenerator(GeneratorInterface $gen)
    {
        $this->generator = $gen;
        
        return $this;
    }
    
    public function defaultDatabase(Connection $conn)
    {
        $this->connection = $conn;
        
        return $this;
    }
    
    
    //------------------------------------------------------------------
    # Event Helpers
    
    public function beforeEntityGenerate(Closure $fn)
    {
        $this->event->addListener(FormatEvents::onTableStart,$fn);
        
        return $this;
    }
    
    
    public function afterEntityGenerate(Closure $fn)
    {
        $this->event->addListener(FormatEvents::onTableEnd,$fn);
        
        return $this;
    }
    
    
    public function beforeFieldGenerate(Closure $fn)
    {
        $this->event->addListener(FormatEvents::onColumnStart,$fn);
        
        return $this;
    }
    
    
    public function afterFieldGenerate(Closure $fn)
    {
        $this->event->addListener(FormatEvents::onColumnEnd,$fn);
        
        return $this;
    }
    
    //------------------------------------------------------------------
    
    
    public function addSimpleField($name)
    {
        $builder = new FieldBuilder($name);
        
        $this->append($builder);
        
        return $builder;
    }
    
    
    
    public function addComplexField($name)
    {
        $builder = new ComplexFieldBuilder($name);
        
        $this->append($builder);
        
        return $builder;
    }
    
}
/* End of File */
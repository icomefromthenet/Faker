<?php
namespace Faker\Components\Engine\Common\Builder;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Locale\LocaleInterface;
use PHPStats\Generator\GeneratorInterface;
use Faker\Components\Engine\Common\Builder\TypeDefinitionInterface;
use Faker\Components\Engine\Common\Builder\ParentNodeInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Doctrine\DBAL\Connection;

/**
  *  Abstract Definition For Type / Selector Definitions
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
abstract class AbstractDefinition implements TypeDefinitionInterface , NodeInterface
{
    
    protected $attributes = array();
    
    protected $parent;
    
    protected $utilities;
    
    protected $locale;
    
    protected $generator;
    
    protected $eventDispatcher;
    
    protected $database;
    
    
    /**
      *  Force All children to not use constructor 
      */    
    public final function __construct(){}
    
    
    /**
      *  Fetch the node managed by this definition
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    public function getNode($id,CompositeInterface $parent)
    {
        
    }
    
    
    //------------------------------------------------------------------
    #ParentNodeInterface
    
    /**
    * Sets the parent node.
    *
    * @param ParentNodeInterface $parent The parent
    *
    * @return ParentNodeInterface
    */
    public function setParent(ParentNodeInterface $parent)
    {
        $this->parent = $parent;

        return $this;
    }    
    
    
    /**
    * Returns the parent node.
    *
    * @return ParentNodeInterface The builder of the parent node
    */
    public function end()
    {
        return $this->parent;
    }
    
    //------------------------------------------------------------------
    #TypeDefinitionInterface
    
    public function locale(LocaleInterface $locale)
    {
        $this->locale = $locale;
        
        return $this;
    }
    
    
    public function generator(GeneratorInterface $gen)
    {
        $this->generator = $gen;
        
        return $this;
    }
    
    
    public function utilities(Utilities $util)
    {
        $this->utilities = $utilities;
        
        return $this;
    }
    
    
    public function eventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
        
        return $this;
    }
    
    
    public function database(Connection $conn)
    {
        $this->database = $conn;
        
        return $this;
    }
    
    /**
    * Sets an attribute on the node.
    *
    * @param string $key
    * @param mixed $value
    *
    * @return AbstractDefinition
    */
    public function attribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }
    
}
/* End of File */
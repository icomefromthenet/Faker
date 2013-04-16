<?php
namespace Faker\Components\Engine\Common\Builder;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Locale\LocaleInterface;
use Faker\Components\Templating\Loader;
use Faker\Components\Engine\Common\Builder\TypeDefinitionInterface;
use Faker\Components\Engine\Common\Builder\ParentNodeInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\EngineException;
use Doctrine\DBAL\Connection;
use PHPStats\Generator\GeneratorInterface;


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
    
    protected $templateLoader;
    
    
    
    /**
      *  Fetch the node managed by this definition
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    abstract public function getNode();
    
    
    //------------------------------------------------------------------
    #ParentNodeInterface
    
    /**
    * Sets the parent node.
    *
    * @param NodeInterface $parent The parent
    *
    * @return NodeInterface
    */
    public function setParent(NodeInterface $parent)
    {
        $this->parent = $parent;

        return $this;
    }    
    
    
    /**
      *  Return the assigned parent
      *
      *  @param access
      *  @return NodeInterface
      */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
    * Returns the parent node.
    *
    * @return ParentNodeInterface The builder of the parent node
    */
    public function end()
    {
        # construct the node from this definition.
        $node = $this->getNode();
        
        # append generators compositeNode to the parent builder.
        $this->parent->append($node);
        
        # return the parent to continue chain.
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
        $this->utilities = $util;
        
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
    
    
    public function templateLoader(Loader $template)
    {
        $this->templateLoader = $template;
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
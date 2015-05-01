<?php
namespace Faker\Components\Engine\Common\Datasource;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Locale\LocaleInterface;
use Faker\Components\Templating\Loader;
use Faker\Components\Engine\Common\Builder\TypeDefinitionInterface;
use Faker\Components\Engine\Common\Builder\NodeInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\EngineException;
use Doctrine\DBAL\Connection;
use PHPStats\Generator\GeneratorInterface;
use Faker\Components\Engine\Common\Composite\DatasourceNode;


/**
  *  Abstract Definition For Datasource definitions (build actual datasources)
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.5
  */
class AbstractDefinition implements TypeDefinitionInterface , NodeInterface
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
    public function getNode()
    {
        throw new EngineException('must be implemented by actual definition');
    }
    
    
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
        $source =  $this->getNode();
        
        # set common properties
        $source->setUtilities($this->utilities);
        $source->setGenerator($this->generator);
        $source->setDatabase($this->database);
        $source->setEventDispatcher($this->eventDispatcher);
        $source->setLocale($this->locale);
        
        
        # set assigned options
        foreach($this->attributes as $n=> $v) {
            $source->setOption($n,$v);
        }
        
        
        $name = 'Datasource';
        
        # Wrap the datasource in a composite node
        $node = new DatasourceNode($name,$this->eventDispatcher,$source);
        
        # append node to the parent builder.
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
    
    //# -----------------------------------------------------------------------
    
    /**
    * Set the datasource id param which identify this source among others
    * and should be unique.
    *
    * @return AbstractDefinition
    * @param string $name the id to use
    */
    public function setDatasourceName($name)
    {
        return $this->attribute('id',$name);
    }
    
    /**
    * Set the datasource id param which identify this source among others
    * and should be unique. (Alias to self::setName)
    *
    * @return AbstractDefinition
    * @param string $id the id to use
    */
    public function setId($id)
    {
        return $this->attribute('id',$id);
    }
    
}
/* End of File */
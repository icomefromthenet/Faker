<?php
namespace Faker\Components\Engine\Common\Formatter;

use Faker\PlatformFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Builder\NodeInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\EngineException;


/**
  *  Abstract For Formatters Builder Definitions
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
abstract class AbstractDefinition implements NodeInterface
{
    
    /**
      *  @var array[string] attributes to apply to the formatter 
      */
    protected $attributes = array();
    
    /**
      *  @var  Faker\Components\Engine\Common\Builder\NodeInterface
      */
    protected $parent;
    
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */
    protected $eventDispatcher;
    
   /**
      *  @var  Faker\Components\Engine\Common\Formatter\FormatterFactory
      */
    protected $formatterFactory;
    
    /**
      *  @var Faker\PlatformFactory 
      */
    protected $platformFactory;

    /**
      *  @var string the Doctrine DBAL plaform to use 
      */
    protected $dbalPlatform;
    
    
    /**
      *  Class Constructor
      *
      *  @access public
      *  @return void
      */
    public function __construct(EventDispatcherInterface $event, FormatterFactory $formatterFactory, PlatformFactory $platformFactory)
    {
        
        $this->eventDispatcher   = $event;
        $this->formatterFactory  = $formatterFactory;
        $this->platformFactory   = $platformFactory;
        
    }
    
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
        $parent = $this->getParent();
        
        $parent->append($this->getNode());
        
        return $parent;
    }
    
    //------------------------------------------------------------------
    
    
    
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
    
    /**
      *  The name of the Doctrine DBAL Platform to use
      *
      *  @access public
      *  @param string $name the platform name
      *  @return AbstractDefinition
      */
    public function usePlatform($name)
    {
        $this->dbalPlatform = $name;
     
        return $this;   
    }
    
    /**
      *  The ouput file format
      *
      *  @example ->outFileFormat('{prefix}_{body}_{suffix}.{ext}')
      *  @param string $format
      *  @return AbstractDefinition
      */
    public function outFileFormat($format)
    {
        $this->attribute(BaseFormatter::CONFIG_OPTION_OUT_FILE_FORMAT,$format);
        return $this;
    }
    
    /**
      *  Set the output encoding scheme, default is utf8
      *
      *  @access public
      *  @example ->outputEncoding('utf8')
      *  @return AbstractDefinition
      */
    public function outputEncoding($method)
    {
        $this->attribute(BaseFormatter::CONFIG_OPTION_OUT_ENCODING,$method);
        return $this;
    }
    
    /**
      *  Split the output file upon a new table, maybe be ignored by
      *  single file formatters like the PHPUnit formatter.
      *
      *  @access public
      *  @param boolean $yes defaults to true to force a split
      *  @return AbstractDefinition
      */
    public function splitOnNewTable($yes = true)
    {
        $this->attribute(BaseFormatter::CONFIG_OPTION_SPLIT_ON_TABLE,$yes);
        return $this;
    }
    
}
/* End of File */
<?php
namespace Faker\Components\Engine\Original\Formatter;

use Faker\Components\Engine\Common\OptionInterface,
    Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Writer\WriterInterface,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\Processor,
    Symfony\Component\Config\Definition\Exception\InvalidConfigurationException,
    Symfony\Component\EventDispatcher\EventDispatcherInterface,
    Symfony\Component\EventDispatcher\EventSubscriberInterface,
    Faker\Text\SimpleStringInterface,
    Doctrine\DBAL\Platforms\AbstractPlatform;
use Faker\Components\Engine\Common\Formatter\FormatEvents;

/*
 * class BaseFormatter
 *
 * All formatters should implement this base class, provides the option interface
 * and default methods
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
abstract class BaseFormatter implements EventSubscriberInterface , OptionInterface
{
    
    const CONFIG_OPTION_SPLIT_ON_TABLE   = 'splitOnTable';
    
    const CONFIG_OPTION_OUT_FILE_FORMAT  = 'outFileFormat';
    
    const CONFIG_OPTION_OUT_ENCODING = 'outEncoding';
    
    /**
      *  @var mixed[] the options array 
      */
    protected $options = array();    
    
    /**
      *  @var  Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $event_dispatcher;
    
    /**
      *  @var Faker\Components\Writer\WriterInterface 
      */
    protected $writer;
    
    /**
      *  @var \Doctrine\DBAL\Types\Type[] 
      */
    protected $column_map = array();
    
     /**
      *  @var use Doctrine\DBAL\Platforms\AbstractPlatform;
      */
    protected $platform;
    
    //  ----------------------------------------------------------------------------
    # EventSubscriberInterface
    
    /**
      *  Fetch Format Event to listen to
      *
      *  @return mixed[]
      *  @access public
      */
    static public function getSubscribedEvents()
    {
        return array(
            FormatEvents::onSchemaStart    => array('onSchemaStart', 0),
            FormatEvents::onSchemaEnd      => array('onSchemaEnd', 0),
            FormatEvents::onTableStart     => array('onTableStart',0),
            FormatEvents::onTableEnd       => array('onTableEnd',0),
            FormatEvents::onRowStart       => array('onRowStart',0),
            FormatEvents::onRowEnd         => array('onRowEnd',0),
            FormatEvents::onColumnStart    => array('onColumnStart',0),
            FormatEvents::onColumnGenerate => array('onColumnGenerate',0),
            FormatEvents::onColumnEnd      => array('onColumnEnd',0),
        
        );
    }    
    
    //  ----------------------------------------------------------------------------
    # Option Interface
    
    /**
      *  Get an option
      *
      *  @parm string the option index
      *  @access public
      */
    public function getOption($name)
    {
        if(isset($this->options[$name]) === false) {
            throw new FakerException("Option at $name does not exist");
        }
        return $this->options[$name];
    }
    
    /**
      *  Sets an option
      *
      *  @param string $name the option index
      *  @param mixed $value the option value
      */
    public function setOption($name,$value)
    {
        $this->options[$name] = $value;
    }
    
    /**
      *  Check if the option is set
      *
      *  @param string $name the option name
      *  @return boolean true if set
      *  @access public
      */
    public function hasOption($name)
    {
        return isset($this->options[$name]);
    }
    
    /**
      *  Build a config validation tree
      *
      *  @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder 
      */
    public function getConfigTreeBuilder()
    {
       $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');
	
	$rootNode->children()
	      ->booleanNode(self::CONFIG_OPTION_SPLIT_ON_TABLE)
                    ->treatNullLike(false)
                    ->defaultValue(false)
                    ->info('Start a new file when a table has finished generating')
                ->end()
                ->scalarNode(self::CONFIG_OPTION_OUT_ENCODING)
                    ->treatNullLike($this->getDefaultOutEncoding())
                    ->defaultValue($this->getDefaultOutEncoding())
                    ->info('An Iconv valid encoding format, defaults set on formatter')
                    ->example('UTF-8')
                    ->validate()
                        ->ifTrue(function($v){
                            return @!mb_convert_encoding('a assci test string', $v, 'UTF-8');
                        })
                        ->then(function($v){
                            throw new FakerException('formatter::outEncoding is not mb valid');
                        })
                    ->end()
                    
                ->end()
                ->scalarNode(self::CONFIG_OPTION_OUT_FILE_FORMAT)
                    ->treatNullLike($this->getOuputFileFormat())
                    ->defaultValue($this->getOuputFileFormat())
                    ->info('The output file format')
                ->end()
	->end();
	
	$this->getConfigExtension($rootNode);
	
	return $treeBuilder;
    }
    
    
    
    /**
      *  Will validate the configuration
      *
      *  @throws FakerException when config validation fails
      */
    public function merge()
    {
        try {
            
            $processor = new Processor();
            $options = $processor->processConfiguration($this, array('config' => $this->options));
            
            # merge validate options (may be defaults set, options filtered)
	    foreach($options as $name => $value ) {
		$this->setOption($name,$value);
	    }
            
        }catch(InvalidConfigurationException $e) {
            throw new FakerException($e->getMessage());
        }
        
    }
    
    //  ----------------------------------------------------------------------------
    # Default Methods
    
    
    /**
      *  Sets the event dispatcher dependency
      *
      *  @param Symfony\Component\EventDispatcher\EventDispatcherInterface $event
      *  @access public
      */
    public function setEventDispatcher(EventDispatcherInterface $event)
    {
        $this->event_dispatcher = $event;
    }
    
    /**
      *  Fetch the event dispatcher
      *
      *  @access public
      *  @return Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    public function getEventDispatcher()
    {
        return $this->event_dispatcher;
    }
    
    
    /**
      *  Sets the write to send formatted string to
      *
      *  @param Faker\Components\Writer\WriterInterface
      */
    public function setWriter(WriterInterface $writer)
    {
        $this->writer = $writer;
    }
    
    /**
      *  Fetches the writer
      *
      *  @return Faker\Components\Writer\WriterInterface
      */
    public function getWriter()
    {
         return $this->writer;
    }
    
    /**
      *  Fetch a associative array column id => Doctrine\DBAL\Types\Type
      *
      *  @return \Doctrine\DBAL\Types\Type[]
      */
    public function getColumnMap()
    {
         return $this->column_map;
    }
    
    /**
      *  Set the column map
      *
      *  @access public
      *  @param mixed[] $map
      */
    public function setColumnMap($map)
    {
        $this->column_map = $map;
    }
    
    /**
      *   Process a single column with the column map
      *   convert the php type into a database representaion for the given platform
      *   assigned to the formatter
      */
    public function processColumnWithMap($key,$value)
    {
        $map = $this->getColumnMap();
        
        if(isset($map[$key]) === false) {
            throw new FakerException('Unknown column mapping at key::'.$key);
        }
        
        return $map[$key]->convertToDatabaseValue($value,$this->getPlatform());
    }
    
    /**
      *  Return the assigned platform
      *
      *  @access public
      *  @return Doctrine\DBAL\Platforms\AbstractPlatform
      */
    public function getPlatform()
    {
        return $this->platform;
    }
    
    /**
      *  Set the platform
      *
      *  @access public
      *  @param Doctrine\DBAL\Platforms\AbstractPlatform $platform
      */
    public function setPlatform(AbstractPlatform $platform)
    {
        $this->platform = $platform;
    }
    
    
    //  ----------------------------------------------------------------------------
    # Abstract Methods
    
    /**
      *  Fetch the formatters Name
      *
      *  @access public
      *  @return string the unique name
      */
    abstract public function getName();
    
    /**
      *  Convert the formatter to xml representation
      *
      *  @return string the xml rep
      *  @access public
      */    
    abstract public function toXml();
    
    /**
      *  Will fetch config extensions used in child formatters that
      *  want to extends the default config tree
      *
      *  @return ArrayNodeDefinition
      *  @access public
      */
    abstract public function getConfigExtension(ArrayNodeDefinition $rootNode);
    
    /**
      *  Defines the default output format for a child formatter, this will
      *  be assigned to the writter
      *
      *  @return string the output format
      *  @access public
      */
    abstract public function getOuputFileFormat();
    
    /**
      *  Defines the default output encoding
      *
      *  @return string the out encoding
      *  @access public
      */
    abstract public function getDefaultOutEncoding();
    
    
}
/* End of File */
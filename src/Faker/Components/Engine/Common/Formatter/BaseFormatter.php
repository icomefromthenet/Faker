<?php
namespace Faker\Components\Engine\Common\Formatter;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Faker\Components\Engine\EngineException;
use Faker\Components\Writer\WriterInterface;
use Faker\Text\SimpleStringInterface;
use Faker\Components\Engine\Common\OptionInterface;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Visitor\DBALGathererVisitor;

/*
 * All formatters should implement this base class, provides the option interface
 * and default methods
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
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
      *  @var Faker\Components\Engine\Common\Visitor\DBALGathererVisitor 
      */
    protected $dbalVisitor;
    
     /**
      *  @var Doctrine\DBAL\Platforms\AbstractPlatform;
      */
    protected $platform;
    
    /**
      *  @var Faker\Components\Engine\Common\Formatter\ValueConverter 
      */
    protected $valueConverter;
    
     //  -------------------------------------------------------------------------
    # Constructor
    
    /**
      *  class constructor
      *
      *  @param EventDispatcherInterface $event
      *  @param WriterInterface $writer
      *  @param AbstractPlatform $platform the doctine platform class
      *  @param mixed[] array of options
      */
    public function __construct(EventDispatcherInterface $event, WriterInterface $writer, AbstractPlatform $platform, DBALGathererVisitor $visitor,$options = array())
    {
        $this->setEventDispatcher($event);
        $this->setWriter($writer);
        $this->setPlatform($platform);
        $this->setVisitor($visitor);
        $this->options = $options;
    }
    
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
            FormatEvents::onSchemaStart    => array('onSchemaStart', 1),
            FormatEvents::onSchemaEnd      => array('onSchemaEnd', 1),
            FormatEvents::onTableStart     => array(
                                                    array('beforeTableStart',255),
                                                    array('onTableStart',1),
                                              ),   
            FormatEvents::onTableEnd       => array(
                                                    array('afterTableEnd',0),
                                                    array('onTableEnd',1),
                                                ),
            FormatEvents::onRowStart       => array('onRowStart',1),
            FormatEvents::onRowEnd         => array('onRowEnd',1),
            FormatEvents::onColumnStart    => array('onColumnStart',1),
            FormatEvents::onColumnGenerate => array('onColumnGenerate',1),
            FormatEvents::onColumnEnd      => array('onColumnEnd',1),
        
        );
    }    
    
    /**
      *  Event hander for  FormatEvents::onTableStart run before
      *  other event handlers and will execute the DBALGathererVisitor
      *
      *  @access public
      */
    public function beforeTableStart(GenerateEvent $event)
    {
        $node    = $event->getType();
        $visitor = $this->getVisitor();
        
        $node->acceptVisitor($visitor);
        
        $this->valueConverter = $visitor->getResult();
    }
    
    /**
      *  Event hander for the FormatEvents::onTableEnd run after
      *  other event handlers will run after other event handlers
      */
    public function afterTableEnd(GenerateEvent $event)
    {
        unset($this->valueConverter);
        $this->getVisitor()->reset();
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
            throw new EngineException("Option at $name does not exist");
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
                            throw new EngineException('formatter::outEncoding is not mb valid');
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
    
    
    //  ----------------------------------------------------------------------------
    # Default Methods
    
     /**
      *  Will validate the configuration
      *
      *  @throws EngineException when config validation fails
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
            throw new EngineException($e->getMessage(),0,$e);
        }
        
    }
    
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
      *  Fetch the DBALVisitor which can convert values through
      *  the internal ValueConverter
      *
      *  @return Faker\Components\Engine\Common\Visitor\DBALGathererVisitor
      *  @access public
      */
    public function getVisitor()
    {
         return $this->dbalVisitor;
    }
    
    /**
      *  Set the DBALVisitor which can convert values through
      *  the internal ValueConverter
      *
      *  @access public
      *  @param Faker\Components\Engine\Common\Visitor\DBALGathererVisitor $visitor
      */
    public function setVisitor($visitor)
    {
        $this->dbalVisitor = $visitor;
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
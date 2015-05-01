<?php
namespace Faker\Components\Engine\Common\Datasource;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

use Faker\Components\Engine\Common\Utilities;
use Faker\Locale\LocaleInterface;
use Faker\Components\Templating\Loader;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\OptionInterface;
use Doctrine\DBAL\Connection;
use PHPStats\Generator\GeneratorInterface;

/**
  *  Abstract Definition For Datasource instances. 
  * 
  *  This abstract will handle all common properties and provide option
  *  handlers and validation processing of them.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.5
  */
abstract class AbstractDatasource implements DatasourceInterface
{
    
    /**
     * @var array[string] of options
     */ 
    protected $options;
    
    /**
      *  @var PHPStats\Generator\GeneratorInterface the random number generator 
      */
    protected $generator;
    
    /**
      *  @var Faker\Locale\LocaleInterface 
      */
    protected $myLocale;
    
    /**
      *  @var Faker\Components\Engine\Common\Utilities
      */
    protected $utilities;
    
    /**
      *  @var  Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $eventDispatcher;
    
    /**
     *  @var Doctrine\DBAL\Connection
     */ 
    protected $database;
    
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
        
        return $this;
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
        
        return $this;
    }

    /**
      *  Set the type with a locale
      *
      *  @access public
      *  @param Faker\Locale\LocaleInterface $locale
      */
    public function setLocale(LocaleInterface $locale)
    {
        $this->myLocale = $locale;
        
        return $this;
    }
    
    /**
      * Fetches this objects locale
      * 
      *  @return Faker\Locale\LocaleInterface
      *  @access public
      */
    public function getLocale()
    {
        return $this->myLocale;
    }
    
    
    /**
      * Get this objects database connections
      * 
      *  @return this
      *  @access public
      */
    public function getDatabase()
    {
        return $this->database;
    }
    
    /**
      * Set this objects database connections
      * 
      *  @return this
      *  @access public
      */
    public function setDatabase(Connection $db)
    {
        $this->database = $db;
     
        return $this;   
    }
   
   
    /**
      * Fetches this objects event dispatcher
      * 
      *  @return Symfony\Component\EventDispatcher\EventDispatcherInterface
      *  @access public
      */ 
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
    
    
    /**
      * Set this objects event dispatcher
      * 
      *  @return Doctrine\DBAL\Connection
      *  @access public
      */ 
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
        
        return $this;
    }
    
    
    //-------------------------------------------------------------------------
    # OptionInterface
    
    /**
     * Sets an option with a value
     * 
     * @param string $name the option index
     * @param mixed $option the option value
     * 
     * @return this
     */ 
    public function setOption($name,$option)
    {
	    $this->options[$name] = $option;
	    
	    return $this;
    }
    
    /**
     * Gets an option 
     * 
     * @param string the option name
     * @return mixed the option value
     */
    public function getOption($name)
    {
    	return $this->options[$name];
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
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
	    $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('config');
	
	    $rootNode->children()
                ->scalarNode('id')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('A unique name for this datasource')
                    ->end()
                ->end();
	
	    # get child custom config options
	    $this->getConfigTreeExtension($rootNode);
	
        
        return $treeBuilder;  
    }
    
    //-------------------------------------------------------------------------
    # Custom Validation Methods
   
    /**
     *  Enhance the config node with extra options
     *
     *  @access public
     *  @return \Symfony\Component\Config\Definition\Builder\NodeDefinition The tree builder
     *
    */
    abstract public function getConfigTreeExtension(NodeDefinition $rootNode);
    
    
    /**
     * Valdiate if this type is ready to execution
     * 
     * @return boolean true if valid
     * @throws EngineException if an error occurs during validation
     */ 
    public function validate()
    {
    	try {
                
                $processor = new Processor();
                $options = $processor->processConfiguration($this, array('config' => $this->options));
    	    
        	    # options may have been filtered reset their values
        	    foreach($options as $name => $value) {
        	    	$this->setOption($name,$value);
        	    }
    	
            }catch(InvalidConfigurationException $e) {
                
                throw new EngineException($e->getMessage());
            }
    	
    	return true;
    }
    
    //--------------------------------------------------------------------------
    # DatasourceInterface 
    
    
    /**
     * This init method is called before any generation is commenced
     * 
     * Can be used to int database connections
     * 
     * @access public
     * @return void
     */ 
    abstract public function initSource();
    
    /**
     * Called during the generator execution, for each needed row
     * 
     * Where fetch a row of data, from interal cache or a database call
     * 
     * @access public
     * @return array of data using a hash index
     */ 
    abstract public function fetchOne();
    
    /**
     * This method is called when the node the
     * source is referenced in has finished its processing
     * 
     * @access public
     * @return void
     */ 
    abstract public function flushSource();
    
    /**
     * Called when the source is no longer needed by any nodes, usually
     * at the end of a generation run.
     * 
     * Can be used to cleanup database references, etc...
     * 
     * @access public
     * @return void
     */ 
    abstract public function cleanupSource();
    
    
}
/* End of File */
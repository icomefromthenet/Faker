<?php
namespace Faker\Components\Engine\Common\Type;

use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\OptionInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\GeneratorCache;
use Faker\Components\Engine\Common\Composite\SerializationInterface;

use PHPStats\Generator\GeneratorInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


/**
 * Base class for all type generators
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
abstract class Type implements TypeInterface, OptionInterface, SerializationInterface
{
    
     /**
      *  @var PHPStats\Generator\GeneratorInterface the random number generator 
      */
    protected $generator;
    
    /**
      *  @var Faker\Locale\LocaleInterface 
      */
    protected $locale;
    
    /**
      *  @var array[mixed] the option values
      */
    protected $options = array();
    
    /**
      *  @var Faker\Components\Engine\Common\Utilities
      */
    protected $utilities;
    
    /**
      *  @var  Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $eventDispatcher;
    
    
    protected $resultCache;
    
    //------------------------------------------------------------------
    # TypeInterface
    
    public function getUtilities()
    {
    	return $this->utilities;
    }
    
    public function setUtilities(Utilities $util)
    {
	    $this->utilities = $util;
    }
    
    public function getGenerator()
    {
	    return $this->generator;
    }
    
    public function setGenerator(GeneratorInterface $generator)
    {
    	$this->generator = $generator;
    }
    
    public function setLocale(LocaleInterface $locale)
    {
    	$this->locale = $locale;
    }
    
    public function getLocale()
    {
    	return $this->locale;
    }

    public function getEventDispatcher()
    {
    	return $this->eventDispatcher;
    }
    
    public function setEventDispatcher(EventDispatcherInterface $event)
    {
    	$this->eventDispatcher = $event;
    }
    
    //------------------------------------------------------------------
    # GeneratorInterface
    
    public function generate($rows,&$values = array(),$last = array())
    {
        throw new EngineException('not implemented');        
    }
    
    public function setResultCache(GeneratorCache $cache)
    {
	    $this->resultCache = $cache;
    }
    
    public function getResultCache()
    {
    	return $this->resultCache;
    }
    
    
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
    
    //------------------------------------------------------------------
    # OptionInterface
    
    public function setOption($name,$option)
    {
	    $this->options[$name] = $option;
    }
    
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
     *  Enhance the config node with extra options
     *
     *  @access public
     *  @return \Symfony\Component\Config\Definition\Builder\NodeDefinition The tree builder
     *
    */
    abstract public function getConfigTreeExtension(NodeDefinition $rootNode);
    
    
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
	    $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('config');
	
	    # get child custom config options
	    $this->getConfigTreeExtension($rootNode);
	
	    $rootNode
            ->children()
                ->scalarNode('locale')
                    ->treatNullLike('en')
                    ->defaultValue('en')
                    ->info('The Default Locale for this schema')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_string($v);
                        })
                        ->then(function($v){
                            throw new InvalidConfigurationException('Type::Locale not in valid list');
                        })
                    ->end()
                ->end()
                ->scalarNode('randomGenerator')
                    ->info('Type of random number generator to use')
                    ->validate()
                        ->ifTrue(function($v) {
                            return empty($v) or !is_string($v);
                        })
                        ->then(function($v){
                            throw new InvalidConfigurationException('Type::randomGenerator must not be empty or string');
                        })
                    ->end()
                ->end()
                ->scalarNode('generatorSeed')
                    ->info('Seed value to use in the generator')
                    ->validate()
                        ->ifTrue(function($v){
                            return ! is_integer($v);
                        })
                        ->then(function($v){
                            throw new InvalidConfigurationException('Type::generatorSeed must be an integer');
                        })
                    ->end()
                ->end()
            ->end()
        ->end();
	
	
        
        return $treeBuilder;  
    }
    
    //-------------------------------------------------------
    # SerializationInterface
    
    public function toXml()
    {
       # hack for the name fixed to alphanumeric
       $str =  '<datatype name="alphanumeric">' . PHP_EOL;
       
       foreach($this->options as $name => $option) {
	    if($name !== 'locale' && $name !== 'name' ) {
		$str .= '<option name="'.$name.'" value="'.$option.'" />' . PHP_EOL;
	    }
       }
       
       return $str . '</datatype>' . PHP_EOL;
    }
    
    
    
    
}
/* End of File */
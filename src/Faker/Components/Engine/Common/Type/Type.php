<?php
namespace Faker\Components\Engine\Common\Type;

use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\OptionInterface;
use Faker\Components\Engine\Common\Utilities;

use PHPStats\Generator\GeneratorInterface;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * Base class for all type generators
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class Type implements TypeInterface, OptionInterface
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
    
    //------------------------------------------------------------------
    # TypeInterface
    
    
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
      *  Generate a value 
      */
    public function generate($rows,$values = array())
    {
        throw new EngineException('not implemented');        
    }
    
    
    /**
      *  Will Merge options with config definition and pass judgement
      *
      *  @access public
      *  @return boolean true if passed
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
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
	throw new EngineException('not implemented');
    }
    
}
/* End of File */
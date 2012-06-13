<?php
namespace Faker\Components\Faker\Composite;

use Faker\Components\Faker\OptionInterface,
    Symfony\Component\Config\Definition\Processor,
    Symfony\Component\Config\Definition\Exception\InvalidConfigurationException,
    Faker\Components\Faker\Exception as FakerException;

/*
 * class BaseComposite the basic class for composite nodes
 */

abstract class BaseComposite implements OptionInterface, CompositeInterface
{
    
    /**
      *  @var string[] array of options 
      */
    protected $options;
    
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        throw new FakerException('Not Implemented');
    }
    
    
    /**
      *  Merge config with the symfony config tree builder
      *
      *  @param string[] array config values
      *  @return void
      *  @throws Faker\Components\Faker\Exception  when validation fails
      */
    public function merge($config)
    {
        try {
            
            $processor = new Processor();
            return $processor->processConfiguration($this, array('config' => $config));
            
        }catch(InvalidConfigurationException $e) {
            
            throw new FakerException($e->getMessage());
        }
    }
    
    /**
      *  @inheritdoc 
      */
    public function setOption($name,$value)
    {
        $this->options[$name]= $value;
    }
    
    /**
      *  @inheritdoc 
      */
    public function getOption($name)
    {
        return $this->options[$name];
    }
   
    //------------------------------------------------------------------
    # Composite Interface (overriden in child interfaces)
    
    public function getParent()
    {
        throw new FakerException('Not Implemented');    
    }

    public function setParent(CompositeInterface $parent)
    {
        throw new FakerException('Not Implemented');    
    }
    
    public function getChildren()
    {
        throw new FakerException('Not Implemented');
    }
    
    public function addChild(CompositeInterface $child)
    {
        throw new FakerException('Not Implemented');
    }
    
    public function getEventDispatcher()
    {
        throw new FakerException('Not Implemented');
    }
    
    public function toXml()
    {
        throw new FakerException('Not Implemented');
    }
    
    public function validate()
    {
        throw new FakerException('Not Implemented');
    }
    
    //------------------------------------------------------------------
}
/* End of File */
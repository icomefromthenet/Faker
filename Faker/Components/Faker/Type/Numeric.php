<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Exception as FakerException;
use Faker\Components\Faker\Utilities;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class Numeric extends Type
{

    //  -------------------------------------------------------------------------

    /**
     * Generate a value
     * 
     * @return string 
     */
    public function generate($rows,$values = array())
    {
        $format = $this->getOption('format');
        
        # add 0 to fore type to be cast as number.
        
        return $this->utilities->generateRandomNum($format) + 0;
    }
    
    
    //  -------------------------------------------------------------------------

    public function toXml()
    {
       return '<datatype name="'.$this->getId().'"></datatype>' . PHP_EOL;
    }
    
    //  -------------------------------------------------------------------------

    
   /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition 
     */
    public function getConfigExtension(ArrayNodeDefinition $rootNode)
    {
        return $rootNode
            ->children()
                ->scalarNode('format')
                ->isRequired()
                ->setInfo('Numeric format to use')
                ->setExample('xxxx.xx | xxxxx.xxxxx | xxxxxx')
                ->end()
            ->end();
    }
    
    //  -------------------------------------------------------------------------

    public function merge($config)
    {
        try {
            
            $processor = new Processor();
            return $processor->processConfiguration($this, array('config' => $config));
            
        }catch(InvalidConfigurationException $e) {
            
            throw new FakerException($e->getMessage());
        }
    }
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        $this->options = $this->merge($this->options);
        return true;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of file */
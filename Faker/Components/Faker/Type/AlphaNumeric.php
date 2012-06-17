<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class AlphaNumeric extends Type
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
        return $this->utilities->generateRandomAlphanumeric($format);
    }
    
    
    //  -------------------------------------------------------------------------

    public function toXml()
    {
       $str =  '<datatype name="'.$this->getId().'">' . PHP_EOL;
       
       foreach($this->options as $name => $option) {
            $str .= '<option name="'.$name.'" value="'.$option.'" />' . PHP_EOL;
       }
       
       return $str . '</datatype>' . PHP_EOL;
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
                ->setInfo('Text format to use')
                ->end()
            ->end();
            
    }
    
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        return true;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of file */
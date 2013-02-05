<?php
namespace Faker\Components\Engine\Original\Type;

use Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Original\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class BooleanType extends Type
{

    //  -------------------------------------------------------------------------

    /**
     * Generate a value
     * 
     * @return string 
     */
    public function generate($rows,$values = array())
    {
        return $this->getOption('value');
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
                ->booleanNode('value')
                ->isRequired()
                ->validate()
                    ->ifTrue(function($v){
                        return !empty($v);    
                    })
                    ->then(function($v){
                       return (boolean) $v; 
                    })
                ->end()    
                ->info('true or false')
                ->example('true | false')
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
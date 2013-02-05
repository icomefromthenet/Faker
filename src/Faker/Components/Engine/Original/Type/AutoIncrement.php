<?php
namespace Faker\Components\Engine\Original\Type;

use Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Original\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class AutoIncrement extends Type
{


    protected $last_value;

    //  -------------------------------------------------------------------------
    
    /**
     * Generate an auto incementing value
     * 
     * @return string 
     */
    public function generate($rows,$values = array())
    {
        
        $start = $this->getOption('start');
        $increment = $this->getOption('increment');
        
        if($this->last_value === null) {
           $this->last_value = $start +0; //force as numeric   
        } else {
            $this->last_value = $this->last_value + $increment;
        }

        $val = $this->last_value;
          
        return $val;
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
                ->scalarNode('increment')
                    ->defaultValue(1)
                    ->info('The increment to add on every loop')
                    ->validate()
                        ->ifTrue(function($v){ return !is_numeric($v); })
                        ->then(function($v){
                           throw new \Faker\Components\Engine\Original\Exception('AutoIncrement::Increment option must be numeric');
                        })
                    ->end()
                ->end()
                ->scalarNode('start')
                    ->validate()
                        ->ifTrue(function($v) {return !is_numeric($v); })
                        ->then(function($v){
                            throw new \Faker\Components\Engine\Original\Exception('AutoIncrement::Start option must be numeric');
                        })
                    ->end()
                ->defaultValue(1)
                ->info('The Value to start with')
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

/* End of File */
<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Range extends Type
{

    /**
      *  @var number the last generated value 
      */
    protected $last_value;
    
    
    public function generate($rows, $values = array())
    {
        $min    = $this->getOption('min');
        $max    = $this->getOption('max');
        $step   = $this->getOption('step');
        $random = $this->getOption('random');
        
        if($step === false && $random === true) {
            $this->last_value = ceil($this->getGenerator()->generate($min,$max));
        } else {
        
            # on first generate call set last value to min
            if($this->last_value === null) {
                $this->last_value = $min;
            }
            else {
               $this->last_value = $this->last_value + $step;
            }
    
            if($this->last_value > $max) {
                $this->last_value = $min;
            }
        
        }
        
        return ($this->last_value +0);
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
                ->scalarNode('min')
                    ->isRequired()
                    ->info('Starting Number')
                    ->example('A numeric number like 1 or 1.67 or 0.87')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_numeric($v);
                        })
                        ->then(function($v){
                            throw new \Faker\Components\Faker\Exception('Range::min Numeric is required');
                        })
                    ->end()
                ->end()
                ->scalarNode('max')
                    ->isRequired()
                    ->example('A numeric number like 1 or 1.67 or 0.87')
                    ->info('The maxium to use in range')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_numeric($v);
                        })
                        ->then(function($v){
                            throw new \Faker\Components\Faker\Exception('Range::max Numeric is required');
                        })
                    ->end()
                ->end()
                ->scalarNode('step')
                    ->defaultValue(false)
                    ->example('1 , 1.5 , 0.6')
                    ->info('Stepping value applied on every increment, not supplied will use random')
                    ->validate()
                        ->ifTrue(function($v){
                            if($v === false) {
                                return true;
                            }
                            
                            return !is_numeric($v);
                        })
                        ->then(function($v){
                            throw new \Faker\Components\Faker\Exception('Range::Step option should be numeric or bool(false) to use random step');
                        })
                    ->end()
                ->end()
                ->booleanNode('random')
                    ->defaultFalse()
                    ->example('false|true')
                    ->info('Enable random step value on every loop, step param must be set to false')
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

/* End of class */
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
        $min = $this->getOption('min');
        $max = $this->getOption('max');
        $step = $this->getOption('step');
        
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
        
        return ($this->last_value +0);
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
                ->scalarNode('min')
                    ->isRequired()
                    ->info('Starting Number')
                    ->example('A numeric number like 1 or 1.67 or 0.87')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_numeric($v);
                        })
                        ->then(function($v){
                            throw new \Faker\Components\Faker\Exception('Number::min Numeric is required');
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
                            throw new \Faker\Components\Faker\Exception('Number::max Numeric is required');
                        })
                    ->end()
                ->end()
                ->scalarNode('step')
                    ->isRequired()
                    ->example('1 , 1.5 , 0.6')
                    ->info('Stepping value applied on every increment, not supplied will use random')
                    ->validate()
                        ->ifTrue(function($v){
                            return !is_numeric($v);
                        })
                        ->then(function($v){
                            throw new \Faker\Components\Faker\Exception('Number::step Numeric is required');
                        })
                    ->end()
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
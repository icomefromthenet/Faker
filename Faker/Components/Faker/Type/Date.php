<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Date extends Type
{

    /**
      *  @var \DateTime the most recent date 
      */
    protected $current_date;


    //-------------------------------------------------------------
    /**
     * Generates a random date from a range
     *
     * @return string 
     */
    public function generate($rows, $values = array())
    {
        $date   = $this->getOption('start');
        $modify = $this->getOption('modify');
        $max    = $this->getOption('max');
        
        # on first call clone the origin date        
        if($this->current_date === null) {
            $this->current_date = clone $date;
        }
        else {
            if(empty($modify) === false) {
                # on consecutive calls apply the modify value
                $this->current_date->modify($modify);
            }
        }
        
        # check if the origin has exceeded the max
        
        if($max instanceof \DateTime) {
            if($this->current_date->getTimestamp() > $max->getTimestamp()) {
                $this->current_date = clone $date;
            }
        }
          
        # return new instance so later calles don't change        
        return clone $this->current_date;
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
                ->scalarNode('start')
                    ->isRequired()
                    ->info('The DateTime strtotime to use as base')
                    ->example('Auguest 18 1818')
                    ->validate()
                        ->ifTrue(function($v){
                            try {
                                  $date = new \DateTime($v);
                                  return true;
                                } catch (\Exception $e) {
                                    throw new \Faker\Components\Faker\Exception($e->getMessage());                                
                                }
                        })
                        ->then(function($v){
                             return new \DateTime($v);
                        })
                    ->end()
                ->end()
                ->scalarNode('max')
                    ->defaultValue(null)
                    ->info('The maxium (strtotime) date to use')
                    ->example('August 15 2012')
                    ->validate()
                        ->ifTrue(function($v){
                            try {
                                  $date = new \DateTime($v);
                                  return true;
                                } catch (\Exception $e) {
                                    throw new \Faker\Components\Faker\Exception($e->getMessage());                                
                                }
                        })
                        ->then(function($v){
                             return new \DateTime($v);
                        })
                    ->end()
                ->end()
                ->scalarNode('modify')
                   ->defaultValue(null)
                   ->info('modify string (strtotime) applied on each increment')
                   ->example('+1 minute')
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
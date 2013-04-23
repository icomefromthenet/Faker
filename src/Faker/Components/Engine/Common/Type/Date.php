<?php
namespace Faker\Components\Engine\Common\Type;

use DateTime;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Date type to make date values
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
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
        
        if(empty($modify) === false && $this->getOption('random') === false) {
            # on consecutive calls apply the modify value
            $this->current_date->modify($modify);
                
            # check if the origin has exceeded the max
        
            if($max instanceof DateTime) {
                if($this->current_date->getTimestamp() > $max->getTimestamp()) {
                        $this->current_date = clone $date;
                }
            }
                
        } else if($this->getOption('random') === true) {
            # random date
            $this->current_date->setTimestamp(ceil($this->getGenerator()->generate($date->getTimestamp(),$max->getTimestamp())));
        } else {
            # using fixed start date
            $this->current_date = clone $date;
        }
        
          
        # return new instance so later calles don't change        
        return clone $this->current_date;
    }
    
    //  -------------------------------------------------------------------------
    
    /**
     * Generates the configuration tree builder.
     *
     */
    public function getConfigTreeBuilder()
    {

        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');
        
        $rootNode
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
                                    throw new EngineException($e->getMessage());                                
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
                                    throw new EngineException($e->getMessage());                                
                                }
                        })
                        ->then(function($v){
                             return new \DateTime($v);
                        })
                    ->end()
                ->end()
                ->scalarNode('modify')
                   ->defaultValue(false)
                   ->info('modify string (strtotime) applied on each increment')
                   ->example('+1 minute')
                ->end()
                ->booleanNode('random')
                   ->defaultValue(false)
                   ->info('select a random datetime between min-max')
                   ->example('true')
                ->end()
            ->end();
            
        return $treeBuilder;
    }

}
/* End of class */
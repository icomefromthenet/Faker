<?php
namespace Faker\Components\Engine\Common\Type;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * AutoIncrement type
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class AutoIncrement extends Type
{

    protected $last_value;

    //  -------------------------------------------------------------------------
    
    /**
     * Generate an auto incementing value
     * 
     * @return string 
     */
    public function generate($rows,&$values = array())
    {
        $start     = $this->getOption('start');
        $increment = $this->getOption('increment');
        
        if($this->last_value === null) {
           $this->last_value = $start + 0; //force as numeric   
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
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');
        
        $rootNode
            ->children()
                ->scalarNode('increment')
                    ->defaultValue(1)
                    ->info('The increment to add on every loop')
                    ->validate()
                        ->ifTrue(function($v){ return !is_numeric($v); })
                        ->then(function($v){
                           throw new EngineException('AutoIncrement::Increment option must be numeric');
                        })
                    ->end()
                ->end()
                ->scalarNode('start')
                    ->validate()
                        ->ifTrue(function($v) {return !is_numeric($v); })
                        ->then(function($v){
                            throw new EngineException('AutoIncrement::Start option must be numeric');
                        })
                    ->end()
                    ->defaultValue(1)
                    ->info('The Value to start with')
                ->end()
            ->end();
            
        return $treeBuilder;    
    }
    
    //  -------------------------------------------------------------------------
}

/* End of File */
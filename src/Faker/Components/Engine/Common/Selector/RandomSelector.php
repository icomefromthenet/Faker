<?php
namespace Faker\Components\Engine\Common\Selector;

use Faker\Components\Engine\Common\Type\Type;
use Faker\Components\Engine\Common\PositionManager;
use Faker\Components\Engine\EngineException;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Random selection of an index with a given set size and step value
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class RandomSelector extends Type
{
    
   
    public function generate($rows,$values = array())
    {
        return round($this->getGenerator()->generate(0,($this->getOption('set'))));
    }
    
    //------------------------------------------------------------------
    
    
    public function getConfigTreeBuilder()
    {

        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');
        
        $rootNode->children()
            ->scalarNode('set')
                ->isRequired()
                ->info('The size of the set to pick from')
                ->example('1 | 2 | 3')
                ->validate()
                    ->ifTrue(function($v){
                        return !is_integer($v) || ((integer) $v < 1);
                    })
                    ->then(function($v){
                        throw new EngineException('RandomSelector::Set integer is required and must be > 0');
                    })
                ->end()
            ->end()
        ->end();
        
        return $treeBuilder;   
    }
}
/* End of File */
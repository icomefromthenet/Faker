<?php
namespace Faker\Components\Engine\Common\Selector;

use Faker\Components\Engine\Common\Type\Type;
use Faker\Components\Engine\Common\PositionManager;
use Faker\Components\Engine\EngineException;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

/**
 * Random selection of an index with a given set size and step value
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class RandomSelector extends Type
{
    
   
    public function generate($rows,&$values = array(),$last = array())
    {
        return round($this->getGenerator()->generate(1,($this->getOption('set'))));
    }
    
    //------------------------------------------------------------------
    
    
    public function getConfigTreeExtension(NodeDefinition $rootNode)
    {

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
                        throw new EngineException('RandomSelector::Set size is required and must be and integer > 0');
                    })
                ->end()
            ->end()
        ->end();
        
        return $rootNode;   
    }
}
/* End of File */
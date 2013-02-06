<?php
namespace Faker\Components\Engine\Common\Selector;

use Faker\Components\Engine\Common\Type;

/**
 * Picks between two alternatives using probability as weight measurement
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class PickSelector extends Type
{
    
   
    public function generate($rows,$values = array())
    {
        $prob = $this->getOption('probabiliy');
        
        return (round($this->getGenerator()->generate(0,100)) <= ($prob * 100)) ? 0 : 1;
    }
    
    
    //------------------------------------------------------------------
    
    
    public function getConfigTreeBuilder()
    {

        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');
        
        $rootNode->children()
            ->scalarNode('probability')
                ->isRequired()
                ->info('')
                ->example('0.3 | 0.4 | 0.5')
                ->validate()
                    ->ifTrue(function($v){
                        return !is_numeric($v) || ($v > 0 || $v < 1);
                    })
                    ->then(function($v){
                        throw new EngineException('PickSelector::Probability must be between 0 and  1');
                    })
                ->end()
            ->end()
        ->end();
        
        return $treeBuilder;   
    }
    
    
}
/* End of File */
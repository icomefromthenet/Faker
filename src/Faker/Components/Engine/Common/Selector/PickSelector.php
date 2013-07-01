<?php
namespace Faker\Components\Engine\Common\Selector;

use Faker\Components\Engine\Common\Type\Type;
use Faker\Components\Engine\Common\PositionManager;
use Faker\Components\Engine\EngineException;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;


/**
 * Picks between two alternatives using weighted probability to skew the number
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class PickSelector extends Type
{
    
    
    public function validate()
    {
        if($this->hasOption('probability') === true) {
            $probability = $this->getOption('probability');
            
            if($probability > 1) {
                $probability = $probability / 100;
            }
            
            $this->setOption('probability',$probability);
        }
        
        return parent::validate();
    }
    
    //------------------------------------------------------------------
    
   
    public function generate($rows,&$values = array())
    {
        $prob      = $this->getOption('probability');
        $generated = round($this->getGenerator()->generate(0,100));
        $rounded   = round($prob * 100);
        
        return $generated <=  $rounded ? 2 : 1;
    }
    
    
    //------------------------------------------------------------------
    
    
    public function getConfigTreeExtension(NodeDefinition $rootNode)
    {
        $rootNode->children()
            ->scalarNode('probability')
                ->isRequired()
                ->info('')
                ->example('0.3 | 0.4 | 0.5')
                ->validate()
                    ->ifTrue(function($v){
                        return !is_numeric($v) || !($v > 0 && $v < 1);
                    })
                    ->then(function($v){
                        throw new EngineException('PickSelector::Probability must be between 0 and  1');
                    })
                ->end()
            ->end()
        ->end();
        
        return $rootNode;   
    }
    
    
}
/* End of File */
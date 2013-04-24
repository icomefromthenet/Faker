<?php
namespace Faker\Components\Engine\Common\Selector;

use Faker\Components\Engine\Common\Type\Type;
use Faker\Components\Engine\Common\PositionManager;
use Faker\Components\Engine\EngineException;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;


/**
 * Alternates an index with a given set size and step value
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class AlternateSelector extends Type
{
    /**
      *  @var Faker\Components\Engine\Common\PositionManager position in the set
      */
    protected $current;
    
    /**
      *  @var Faker\Components\Engine\Common\PositionManager position in the step
      */
    protected $currentStep;
    
    
   
    public function generate($rows,&$values = array())
    {
        $position = $this->current->position();
        
        if($this->currentStep->atLimit()) {
            $this->current->increment();  
        }
        
        $this->currentStep->increment();
        
        return $position;
    }
    
    
    //------------------------------------------------------------------
    
    
    public function getConfigTreeBuilder()
    {

        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');
        
        $rootNode->children()
            ->scalarNode('step')
                ->isRequired()
                ->info('The number of passes to make before alternating must be an integer')
                ->example('1 | 2 | 3')
                ->validate()
                    ->ifTrue(function($v){
                        return !is_integer($v) || ((integer) $v < 1);
                    })
                    ->then(function($v){
                        throw new EngineException('AlternateSelector::Step integer is required and must be > 0');
                    })
                ->end()
            ->end()
            ->scalarNode('set')
                ->isRequired()
                ->info('The size of the set to alternate over')
                ->example('1 | 2 | 3')
                ->validate()
                    ->ifTrue(function($v){
                        return !is_integer($v) || ((integer) $v < 1);
                    })
                    ->then(function($v){
                        throw new EngineException('AlternateSelector::Set integer is required and must be > 0');
                    })
                ->end()
            ->end()
        ->end();
        
        return $treeBuilder;   
    }
    
    
    
    public function validate()
    {
        parent::validate();
        
        $step       = $this->getOption('step');
        $set        = $this->getOption('set');
        
        $this->current     = new PositionManager($set);
        $this->currentStep = new PositionManager($step);
        
        return true;
    }
    
}
/* End of File */
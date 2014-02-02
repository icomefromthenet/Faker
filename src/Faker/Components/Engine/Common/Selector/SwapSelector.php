<?php
namespace Faker\Components\Engine\Common\Selector;

use Faker\Components\Engine\Common\Type\Type;
use Faker\Components\Engine\Common\PositionManager;
use Faker\Components\Engine\EngineException;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;


/**
 * Swaps to an index after x tries
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class SwapSelector extends Type
{
    /**
      *  @var Faker\Components\Engine\Common\PositionManager position in the set
      */
    protected $current;
    
    /**
      *  @var array[Faker\Components\Engine\Common\PositionManager]
      */
    protected $swaps = array();
    
   
    public function generate($rows,&$values = array(),$last = array())
    {
        $index = $this->current->position();
        
        if($this->swaps[$index-1]->atLimit()) {
            $this->current->increment();
        } 
        
        $this->swaps[$index-1]->increment();
        
        return $index;
    }
    
    
    //------------------------------------------------------------------
    
    
    public function getConfigTreeExtension(NodeDefinition $rootNode)
    {
        return $rootNode;   
    }
    
    
    public function validate()
    {
        parent::validate();
        
        $this->current = new PositionManager(count($this->swaps));
    }
    
    
    /**
      *  Registers a swap
      *
      *  @access public
      *  @param PositionManager $manager
      */
    public function registerSwap(PositionManager $manager)
    {
        $this->swaps[] = $manager;
    }
    
}
/* End of File */
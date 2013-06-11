<?php
namespace Faker\Components\Engine\XML\Builder;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\XML\Composite\SelectorNode;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Selector\RandomSelector;
use Faker\Components\Engine\Common\Builder\SelectorRandomBuilder as BaseBuilder;
use Faker\Components\Engine\Common\Builder\TypeDefinitionInterface;


/**
  *  Allows the Random Selector to be created and populated with types
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class SelectorRandomBuilder extends BaseBuilder implements TypeDefinitionInterface 
{
    
    
    //------------------------------------------------------------------
    # Node Collection
    
    /**
      *  Fetch the node managed by this definition
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    public function getNode()
    {
        # construct the selector type
        $type = new RandomSelector();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        $type->setOption('set',count($this->children()));
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        
        # return the composite generator selectorNode
        return new SelectorNode('selectorNode',$this->eventDispatcher,$type); 
    }
    
}
/* End of file */

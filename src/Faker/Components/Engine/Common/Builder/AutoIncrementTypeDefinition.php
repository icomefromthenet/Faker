<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\AutoIncrement;

/**
  *  Definition for the AutoIncrement Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class AutoIncrementTypeDefinition extends AbstractDefinition
{
    
    /**
    * Instantiate and configure the node according to this definition
    *
    * @return Faker\Components\Engine\Common\Composite\TypeNode The node instance
    *
    * @throws InvalidDefinitionException When the definition is invalid
    */
    public function getNode()
    {
        $type = new AutoIncrement();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode('AutoIncrement',$this->eventDispatcher,$type);
    }
    
    /**
      *  Sets the start value
      *
      *  @access public
      *  @param numeric $value a value to start on
      *  @example $type->startAtValue(100);
      *  @return AutoIncrementTypeDefinition
      */
    public function startAtValue($value)
    {
        $this->attribute('start',$value);
        return $this;
    }
    
    /**
      *  Set the increment value
      *
      *  @access public
      *  @return AutoIncrementTypeDefinition
      *  @param numeric $value the value to increment by
      */
    public function incrementByValue($value)
    {
        $this->attribute('increment',$value);
        return $this;
    }
}
/* End of File */
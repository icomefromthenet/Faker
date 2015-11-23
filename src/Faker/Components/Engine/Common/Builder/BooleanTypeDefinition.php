<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\BooleanType;

/**
  *  Definition for the Boolean Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class BooleanTypeDefinition extends AbstractDefinition
{
    
    public function endBooleanField()
    {
        return $this->end();
    }
    
    /**
    * Instantiate and configure the node according to this definition
    *
    * @return Faker\Components\Engine\Common\Composite\CompositeInterface The node instance
    *
    * @throws InvalidDefinitionException When the definition is invalid
    */
    public function getNode()
    {
        $type = new BooleanType();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode('Boolean',$this->eventDispatcher,$type);
    }
    
    /**
      *  Set the boolean value to use
      *
      *  @access public
      *  @return BooleanTypeDefinition
      *  @param bool $value the bool value to use
      *  @example $type->value(false);
      */
    public function value($value)
    {
        $this->attribute('value',(boolean)$value);
        return $this;
    }
    
}
/* End of File */
<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\ConstantNumber;

/**
  *  Definition for the ConstantNumber Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ConstantNumberTypeDefinition extends AbstractDefinition
{
    
    
    public function endConstantField()
    {
        return $this->end();
    }
    
    /**
    * Instantiate and configure the node according to this definition
    *
    * @return Faker\Components\Engine\Common\Composite\TypeNode The node instance
    *
    * @throws InvalidDefinitionException When the definition is invalid
    */
    public function getNode()
    {
        $type = new ConstantNumber();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode('ConstantNumber',$this->eventDispatcher,$type);
    }
    
    /**
      *  Sets the value to return.
      *
      *  @access public
      *  @return ConstantNumberTypeDefinition
      *  @param numeric $value
      *  @example $type->value(100);
      */
    public function value($value)
    {
        $this->attribute('value',$value);
        return $this;
    }
    
    /**
      *  The Type to cast the geneated value into
      *
      *  @access public
      *  @param string $type the type to use (string|boolean|integer|float|double)
      *  @example $type->cast('float');
      *  @return ConstantNumberTypeDefinition
      */
    public function cast($value)
    {
        $this->attribute('type',$value);
        return $this;
    }
    
}
/* End of File */
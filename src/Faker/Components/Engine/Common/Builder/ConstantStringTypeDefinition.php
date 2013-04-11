<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\ConstantString;

/**
  *  Definition for the ConstantString Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ConstantStringTypeDefinition extends AbstractDefinition
{
    
    /**
    * Instantiate and configure the node according to this definition
    *
    * @return Faker\Components\Engine\Common\Composite\CompositeInterface The node instance
    *
    * @throws InvalidDefinitionException When the definition is invalid
    */
    public function getNode()
    {
        $type = new ConstantString();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return $type;
    }
    
    
    public function value($value)
    {
        $this->attribute('value',$value);
        return $this;
    }
    
    
    public function cast($value)
    {
        $this->attribute('type',$value);
        return $this;
    }
    
}
/* End of File */
<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\Numeric;

/**
  *  Definition for the Numeric Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class NumericTypeDefinition extends AbstractDefinition
{
    
    /**
    * Instantiate and configure the node according to this definition
    *
    * @return Faker\Components\Engine\Common\Composite\CompositeInterface The node instance
    *
    * @throws InvalidDefinitionException When the definition is invalid
    */
    public function getNode($id, CompositeInterface $parent)
    {
        $type = new Numeric();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode($id,$parent,$this->eventDispatcher,$type);
    }
    
    
    public function format($value)
    {
        $this->attribute('format',$value);
        return $this;
    }
}
/* End of File */
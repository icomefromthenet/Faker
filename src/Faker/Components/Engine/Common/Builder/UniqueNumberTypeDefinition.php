<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\UniqueNumber;

/**
  *  Definition for the UniqueNumber Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class UniqueNumberTypeDefinition extends AbstractDefinition
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
        $type = new UniqueNumber();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode('UniqueNumber',$this->eventDispatcher,$type);
    }
    
    /**
      *  The output format template
      *
      *   DSL is simple
      *   x (0-9)
      *   X (1-9)
      *
      *  @access public
      *  @return UniqueNumberDefinition
      *  @example $type->format('xxxxx.xxxxx');
      *  @param string $value the template 
      */
    public function format($value)
    {
        $this->attribute('format',$value);
        return $this;
    }
}
/* End of File */
<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\UniqueString;

/**
  *  Definition for the UniqueString Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class UniqueStringTypeDefinition extends AbstractDefinition
{
    
    public function endUniqueStringField()
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
        $type = new UniqueString();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode('UniqueString',$this->eventDispatcher,$type);
    }
    
    /**
      *  Set the output format to use.
      *
      *  @param string $value the string format
      *  @example $type->format('CcVDx');
      *  @return UniqueStringTypeDefinition
      *
      *  1.   C, c, E - any consonant (Upper case, lower case, any)
      *  2.   V, v, F - any vowel (Upper case, lower case, any)
      *  3.   L, l, D - any letter (Upper case, lower case, any)
      *  4.   X       - 1-9
      *  5.   x       - 0-9
      *  6.   H       - 0-F
      *  
      */
    public function format($value)
    {
        $this->attribute('format',$value);
        return $this;
    }
   
}
/* End of File */
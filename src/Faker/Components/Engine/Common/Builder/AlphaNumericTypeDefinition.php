<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\AlphaNumeric;

/**
  *  Definition for the AlphaNumeric Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class AlphaNumericTypeDefinition extends AbstractDefinition
{
    
   public function endAlphaNumericField()
   {
       return $this->end();
   }
   
   public function getNode()
    {
        $type = new AlphaNumeric();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode('AlphaNumeric',$this->eventDispatcher,$type);
    }
    
    /**
      *  Set the output format to use.
      *
      *  @param string $value the string format
      *  @example $type->format('CcVDx');
      *  @return AlphaNumericTypeDefinition
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
    
    /**
      *  Set the minimum number of times to repeat the format string
      *
      *  If a max is used and not idential as the min value a middle value chosen at random
      *  
      *  @access public
      *  @return AlphaNumericTypeDefinition
      *  @example $type->repeatMin(3);
      *  @param integer $value the min repeat value 
      *  
      */
    public function repeatMin($value)
    {
        $this->attribute('repeatMin',$value);
        return $this;
    }
    
    /**
      *  Set the maximum number of times to repeat the format string
      *
      *  If a min is used and not idential as the max value a middle value chosen at random
      *
      *  @access public
      *  @return AlphaNumericTypeDefinition
      *  @param integer $value the max value
      *  @example $type->repeatMax(5);
      *  
      */
    public function repeatMax($value)
    {
        $this->attribute('repeatMax',$value);
        return $this;
    }
}
/* End of File */
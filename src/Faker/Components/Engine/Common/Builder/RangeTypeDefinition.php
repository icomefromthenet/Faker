<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\Range;

/**
  *  Definition for the Range Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class RangeTypeDefinition extends AbstractDefinition
{
    
    public function endRangeField()
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
        $type = new Range();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode('Range',$this->eventDispatcher,$type);
    }
    
    /**
      *  Sets the start value in range
      *
      *  @access public
      *  @param numeric $value a value to start on
      *  @example $type->startAtValue(100);
      *  @return RangeTypeDefinition
      */
    public function startAtValue($value)
    {
        $this->attribute('min',$value);
        return $this;
    }
    
    /**
      * Sets the maximum value in range
      *
      * @access public
      * @return RangeTypeDefinition
      * @example $type->stopAtValue(100);
      * @param numeric $value the stop value
      */
    public function stopAtValue($value)
    {
        $this->attribute('max',$value);
        return $this;
    }
    
    /**
      *  Set the increment value added on each iteration
      *
      *  @access public
      *  @return RangeTypeDefinition
      *  @param numeric $value the value to increment by
      *  @example $type->incrementByValue(4);
      */
    public function incrementByValue($value)
    {
        $this->attribute('step',$value);
        return $this;
    }
    
    /**
      *  Sets the window step value defaults to zero
      *  After full iteration this value is added to 
      *  start before next iteration begins
      *
      *  @access public
      *  @param numeric $value
      *  @example $type->incrementWindow(1);
      *  @return RangeTypeDefinition
      */
    public function incrementWindow($value)
    {
        $this->attribute('windowStep',$value);
        return $this;
    }
    
    /**
      * Use a random value in the range
      *
      * @access public
      * @param boolean $value true to use random increment
      * @return RangeTypeDefinition
      * @example $type->useRandomIncrement(true); 
      */
    public function useRandomIncrement($value)
    {
        $this->attribute('random',$value);
        return $this;
    }
    
    /**
      *  Sets the rounder default is to ignore
      *
      *  @access public
      *  @param integer $x the number of decimal places
      *  @example $type->roundToXDecimals(0); [no decimal]
      *  @return RangeTypeDefinition
      */
    public function roundToXDecimals($x)
    {
        $this->attribute('round',$x);
        return $this;
    }
    
}
/* End of File */
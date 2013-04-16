<?php
namespace Faker\Components\Engine\Common\Builder;

use DateTime;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\Date;

/**
  *  Definition for the Date Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class DateTypeDefinition extends AbstractDefinition
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
        $type = new Date();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode('Date',$this->eventDispatcher,$type);
    }
    
    /**
      *  Set the start time
      *
      *  @access public
      *  @return DateTypeDefinition
      *  @param DateTime $value 
      */
    public function startDate(DateTime $value)
    {
        $this->attribute('start',$value);
        return $this;
    }
    
    /**
      *  Set the max date
      *
      *  @access public
      *  @return DateTypeDefinition
      *  @param DateTime $value
      */
    public function maxDate(DateTime $value)
    {
        $this->attribute('max',$value);
        return $this;
    }
    
    /**
      *  Set the increment value
      *
      *  @access public
      *  @return DateTypeDefinition
      *  @param string $value a strtotime string
      */
    public function modifyTime($value)
    {
        $this->attribute('modify',$value);
        return $this;
    }
    
    /**
      *  Use random increment between min and max
      *
      *  @access public
      *  @return DateTypeDefinition
      *  @param boolean $value use random increment
      */
    public function pickRandomBetweenMinMax($value)
    {
        $this->attribute('random',$value);
        return $this;
    }
    
}
/* End of File */
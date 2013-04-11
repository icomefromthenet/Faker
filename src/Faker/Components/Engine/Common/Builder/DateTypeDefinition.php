<?php
namespace Faker\Components\Engine\Common\Builder;

use DateTime;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\Date;

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
    * @return Faker\Components\Engine\Common\Composite\CompositeInterface The node instance
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
        
        return $type;
    }
    
    
    public function start(DateTime $value)
    {
        $this->attribute('start',$value);
        return $this;
    }
    
    
    public function max(DateTime $value)
    {
        $this->attribute('max',$value);
        return $this;
    }
    
    
    public function modify($value)
    {
        $this->attribute('modify',$value);
        return $this;
    }
    
    public function random($value)
    {
        $this->attribute('random',$value);
    }
    
}
/* End of File */
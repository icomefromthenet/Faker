<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\AlphaNumeric;

/**
  *  Definition for the Custom datatypes added by user 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class DefaultTypeDefinition extends AbstractDefinition
{
    
    protected $className;
    
    /**
    * Instantiate and configure the node according to this definition
    *
    * @return Faker\Components\Engine\Common\Composite\CompositeInterface The node instance
    *
    * @throws InvalidDefinitionException When the definition is invalid
    */
    public function getNode()
    {
        $class = $this->className;
        
        $type = new $class();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return $type;
    }
    
    
    public function className($class)
    {
        $this->className = $class;
    }
    
    public function option($name,$value)
    {
        $this->attribute($name,$value);
        return $this;
    }
    
}
/* End of File */
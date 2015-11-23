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
    * @return Faker\Components\Engine\Common\Composite\TypeNode The node instance
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
        
        return new TypeNode('DefaultType',$this->eventDispatcher,$type);
    }
    
    /**
      *  FQN of the Custom Type
      *
      *  @param string the class name
      *  @return DefaultTypeDefinition
      *  @access protected
      */    
    public function typeName($class)
    {
        $this->className = $class;
        return $this;
    }
    
    /**
     * @see self::end()
     */ 
    public function endDefaultField()
    {
        return $this->end();
    }
    
}
/* End of File */
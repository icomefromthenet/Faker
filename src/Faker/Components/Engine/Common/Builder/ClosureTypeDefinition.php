<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\ClosureType;
use Faker\Components\Engine\EngineException;

/**
  *  Definition for the Closure Types
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ClosureTypeDefinition extends AbstractDefinition
{
    
    protected $closure;
    
    
    public function endClosureField()
    {
        return $this->end();
    }
    
    
    /**
    * Instantiate and configure the node according to this definition
    *
    * @return Faker\Components\Engine\Common\Composite\CompositeInterface The node instance
    *
    * @throws InvalidDefinitionException When the definition is invalid
    */
    public function getNode()
    {
        
        if($this->closure === null) {
            throw new EngineException('ClosureTypeDefinition must have a callable closure set before building the ClosureType');
        }
        
        $type = new ClosureType();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        $type->setClosure($this->closure);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode('ClosureType',$this->eventDispatcher,$type);
    }
    
    /**
      *  Pass in a callable
      *
      *  @param \Closure the function to execute
      *  @return $this;
      */
    public function execute(\Closure $callable)
    {
        $this->closure = $callable;
        
        return $this;
    }
    
    
    
}
/* End of File */
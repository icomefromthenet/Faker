<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\Names;

/**
  *  Definition for the Names Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class NamesTypeDefinition extends AbstractDefinition
{
    
    
    public function endNamesField()
    {
        return $this->end();
    }
    
    /**
     * Called people field in NodeBuilder
     *  
     * @return NodeInterface
     */ 
    public function endPeopleField()
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
        $type = new Names($this->database);
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode('Names',$this->eventDispatcher,$type);
    }
    
 
    /**
      *  The output format template
      *
      *  @access public
      *  @example $type->format('{fname} {inital} {lname}');
      *  @return NamesTypeDefinition
      *  @param string $value the output template
      */
    public function format($value)
    {
        $this->attribute('format',$value);
        return $this;
    }
 
}
/* End of File */
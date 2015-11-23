<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\Regex;

/**
  *  Definition for the Regex Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class RegexTypeDefinition extends AbstractDefinition
{
    
    public function endRegexField()
    {
        return $this->end();
    }
    
    /**
    * Instantiate and configure the node according to this definition
    *
    * @return Faker\Components\Engine\Common\Composite\TypeNode instance
    *
    * @throws InvalidDefinitionException When the definition is invalid
    */
    public function getNode()
    {
        $type = new Regex();
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode('Regex',$this->eventDispatcher,$type);
    }
    
    /**
      *  Set the regex format to use
      *
      *  @param array[string] $countries a list of  ISO_3166-1 country codes
      *  @return RegexTypeDefinition
      *  @access public
      *  @exaple $type->format('[a-z]');
      */
    public function regex($format)
    {
        $this->attribute('format',$format);
        return $this;
    }
    
}
/* End of File */
<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\Cities;

/**
  *  Definition for the Cities Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class CitiesTypeDefinition extends AbstractDefinition
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
        $type = new Cities($this->database);
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode('Cities',$this->eventDispatcher,$type);
    }
    
    /**
      *  Set the countries to load cities from
      *
      *  @param array[string] $countries a list of  ISO_3166-1 country codes
      *  @return CitiesTypeDefinition
      *  @access public
      *  @exaple $type->countries(array('AU','GB'));
      */
    public function countries(array $countries)
    {
        $this->attribute('countries',$countries);
        return $this;
    }
    
}
/* End of File */
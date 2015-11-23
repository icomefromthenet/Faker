<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\Country;


/**
  *  Definition for the Country Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class CountryTypeDefinition extends AbstractDefinition
{
    
    public function endCountryField()
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
        $type = new Country($this->database);
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
        return new TypeNode('Country',$this->eventDispatcher,$type);
    }
    
    
    /**
      *  List of country codes to fetch full name, if not included
      *  countries are chosen at random
      *  
      *  @link ftp://ftp.fu-berlin.de/doc/iso/iso3166-countrycodes.txt
      *  @access public
      *  @example $type->countries(array('AU','GB'))
      *  @return CountryTypeDefinition
      */
    public function countries(array $value)
    {
        $this->attribute('countries',$value);
        
        return $this;
    }
    
}
/* End of File */
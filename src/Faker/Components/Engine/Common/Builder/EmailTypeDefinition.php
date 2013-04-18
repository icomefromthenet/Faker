<?php
namespace Faker\Components\Engine\Common\Builder;

use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Type\Email;

/**
  *  Definition for the Email Datatype
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class EmailTypeDefinition extends AbstractDefinition
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
        $type = new Email($this->database);
        $type->setGenerator($this->generator);
        $type->setUtilities($this->utilities);
        $type->setLocale($this->locale);
        
        foreach($this->attributes as $attribute => $value) {
            $type->setOption($attribute,$value);
        }
        
       return new TypeNode('Email',$this->eventDispatcher,$type);
    }
    
    /**
      *  List of params to be evaluate by text generator used in alphanumeric type.
      *  need to use the same DSL provided by AlphaNumericType 
      *
      *  @access public
      *  @return EmailTypeDefinition
      *  @param array[string] $value
      *  @example $type->params(array('custom1' => 'CCCCC','custon2'=> 'cccc'));
      */
    public function params(array $value)
    {
        $this->attribute('params',json_encode($value));
        return $this;
    }
    
    /**
      *  List of domains suffixes to use if none included a small default list is used
      *  these domains don't have to be valid.
      *
      *  @return EmailTypeDefinition
      *  @access public
      *  @example $type->domains(array('com.au','au','org'))
      *  @param array[string] $value the list of domain suffixes to use
      */
    public function domains(array $value)
    {
        $this->attribute('domains',$value);
        return $this;
    }
    
    /**
      *  Format of the output to use
      *
      *  @example $type->format('{fname}{lname}{alpha}@{alpha}.{domain}');
      *  @return EmailTypeDefinition
      *  @access public
      */
    public function format($value)
    {
        $this->attribute('format',$value);
        return $this;
    }
    
}
/* End of File */
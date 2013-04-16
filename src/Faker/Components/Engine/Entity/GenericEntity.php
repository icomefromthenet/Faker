<?php
namespace Faker\Components\Engine\Entity;

use Faker\Components\Engine\EngineException;

/**
  *  A container for entity values
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class GenericEntity
{
    
    protected $values = array();
    
    /**
      *  Class Constructor
      *
      *  @param array $fields => $values 
      */
    public function __construct($fields = array())
    {
        foreach ($fields as $name => $value) {
            $this->$name = $value;
        }
    }
    
    
    public function __set($name, $value)
    {  
        $this->values[$name] = $value;
    }
   
    
    public function __get($name)
    {
        if (!array_key_exists($name, $this->values)) {
            throw new EngineException('The field ' . $name . ' has not been set for this entity yet.');    
        }
        
        return $this->values[$name];
    }
       
    public function __unset($name)
    {
        if (array_key_exists($name, $this->values)) {
            unset($this->values[$name]);
        }
    }
      
    public function toArray()
    {
        return $this->values;
    }             

}
/* End of File */
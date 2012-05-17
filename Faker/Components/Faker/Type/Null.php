<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Exception as FakerException;

class Null extends Type
{

    public function generate($rows, $values = array())
    {
        return NULL;
    }

    
    //  -------------------------------------------------------------------------
    
     public function validate()
    {
	return true;        
    }
    
    //  -------------------------------------------------------------------------
    
    public function merge($config)
    {
	return true;
    }

    //  -------------------------------------------------------------------------
}
/* End of class */
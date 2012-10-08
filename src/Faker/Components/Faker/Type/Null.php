<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Exception as FakerException,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

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
    
    public function getConfigExtension(ArrayNodeDefinition $rootNode)
    {
	return $rootNode;
    }
}
/* End of class */
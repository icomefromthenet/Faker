<?php
namespace Faker\Extension\Faker\Type;

use Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Original\Type\Type,
    Faker\Components\Engine\Original\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Demo extends Type
{

    // -------------------------------------------------------------------------

    /**
    * Generate a value
    *
    * @return string
    */
    public function generate($rows,$values = array())
    {
        return null;
    }
    
    
   
    // -------------------------------------------------------------------------
    
    /**
    * Generates the configuration tree builder.
    *
    * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
    */
    public function getConfigExtension(ArrayNodeDefinition $rootNode)
    {
        return $rootNode;
            
    }
    
    
    // -------------------------------------------------------------------------
}
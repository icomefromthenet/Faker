<?php
namespace Faker\Extension\Faker\Type;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Type\Type;
use Faker\Components\Engine\Common\Utilities;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

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
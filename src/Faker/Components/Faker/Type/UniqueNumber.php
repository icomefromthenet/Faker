<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class UniqueNumber extends Type
{

    /**
     * A cache of previous generated GUIDs
     * 
     */
    static $generated = array();

    //  -------------------------------------------------------------------------

    
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition 
     */
    public function getConfigExtension(ArrayNodeDefinition $rootNode)
    {
        return $rootNode
            ->children()
                ->scalarNode('format')
                  ->defaultValue('XXXXXXXX')
                  ->info('unique format to use')
                ->end()
            ->end();
    }
    
    //  -------------------------------------------------------------------------

    public function validate()
    {
        return true;
    }
    
    //-------------------------------------------------------
    /**
     * Generates a unique number
     * 
     * @return string 
     */
     public function generate($rows, $values = array())
     {
        $guid   = null;
        $ok     = false;
        $format = $this->getOption('format');
        
        do  {
            $guid = $this->utilities->generateRandomNum($format,$this->getGenerator());
        
            if(in_array($guid, self::$generated) === false) {
                $ok = true;
            }    
            
        } while($ok === false);
        
        
        return $guid + 0;
    }

    //------------------------------------------------------------
}

/* End of file */
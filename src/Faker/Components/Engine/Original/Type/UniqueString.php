<?php
namespace Faker\Components\Engine\Original\Type;

use Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Original\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class UniqueString extends Type
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
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition The tree builder
     */
    public function getConfigExtension(ArrayNodeDefinition $rootNode)
    {
      return $rootNode
            ->children()
                ->scalarNode('format')
                  ->defaultValue('XXXXXXXX-XXXX-XXXX-XXXX-XXXX-XXXXXXXX')
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
     * Generates a unique string
     * 
     * @return string 
     */
     public function generate($rows, $values = array())
     {
        $guid   = null;
        $ok     = false;
        $format = $this->getOption('format');
        
        do  {
            $guid = $this->utilities->generateRandomAlphaNumeric($format,$this->getGenerator(),$this->getLocale());
        
            if(in_array($guid, self::$generated) === false) {
                $ok = true;
            }    
            
        } while($ok === false);
        
        array_push(self::$generated,$guid);
        
        return (string) $guid;
    }

    //------------------------------------------------------------
}

/* End of file */

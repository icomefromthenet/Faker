<?php
namespace Faker\Components\Engine\Common\Type;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

/**
 * Unique number with cache
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
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
     */
    public function getConfigTreeExtension(NodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->scalarNode('format')
                  ->defaultValue('XXXXXXXX')
                  ->info('unique format to use')
                ->end()
            ->end();
        
        return $rootNode;
    }
    
    
    //-------------------------------------------------------
    /**
     * Generates a unique number
     * 
     * @return string 
     */
     public function generate($rows, &$values = array())
     {
        $guid   = null;
        $ok     = false;
        $format = $this->getOption('format');
        
        do  {
            $guid = $this->getUtilities()->generateRandomNum($format,$this->getGenerator());
        
            if(in_array($guid, self::$generated) === false) {
                $ok = true;
            }    
            
        } while($ok === false);
        
        array_push(self::$generated,$guid);
        
        return $guid + 0;
    }

    //------------------------------------------------------------
}

/* End of file */

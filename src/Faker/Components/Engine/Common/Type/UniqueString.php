<?php
namespace Faker\Components\Engine\Common\Type;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Unique string with cache
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class UniqueString extends Type
{

    /**
     * A cache of previous generated
     * 
     */
    static $generated = array();

    
   //  -------------------------------------------------------------------------

    /**
     * Generates the configuration tree builder.
     *
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');
        
        $rootNode
            ->children()
                ->scalarNode('format')
                  ->defaultValue('XXXXXXXX-XXXX-XXXX-XXXX-XXXX-XXXXXXXX')
                  ->info('unique format to use')
                ->end()
            ->end();
        return $treeBuilder;
    }
    
    
    //-------------------------------------------------------
    /**
     * Generates a unique string
     * 
     * @return string 
     */
     public function generate($rows, &$values = array())
     {
        $guid   = null;
        $ok     = false;
        $format = $this->getOption('format');
        
        do  {
            $guid = $this->getUtilities()->generateRandomAlphaNumeric($format,$this->getGenerator(),$this->getLocale());
        
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

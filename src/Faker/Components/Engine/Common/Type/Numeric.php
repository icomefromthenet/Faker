<?php
namespace Faker\Components\Engine\Common\Type;

use DateTime;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Numeric type for numbers
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class Numeric extends Type
{

    //  -------------------------------------------------------------------------

    /**
     * Generate a value
     * 
     * @return string 
     */
    public function generate($rows,$values = array())
    {
        $format = $this->getOption('format');
        
        # add 0 to fore type to be cast as number.
        
        return $this->getUtilities()->generateRandomNum($format,$this->getGenerator()) + 0;
    }
    
    
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
                    ->isRequired()
                    ->info('Numeric format to use')
                    ->example('xxxx.xx | xxxxx.xxxxx | xxxxxx')
                ->end()
            ->end();
            
        return $treeBuilder;
    }
    
}
/* End of file */
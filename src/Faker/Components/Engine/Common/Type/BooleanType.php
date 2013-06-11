<?php
namespace Faker\Components\Engine\Common\Type;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

/**
 * Boolean type
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class BooleanType extends Type
{

    //  -------------------------------------------------------------------------

    /**
     * Generate a value
     * 
     * @return string 
     */
    public function generate($rows,&$values = array())
    {
        return $this->getOption('value');
    }
    
    
    //  -------------------------------------------------------------------------

    /**
     * Generates the configuration tree builder.
     *
     */
    public function getConfigTreeExtension(NodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->booleanNode('value')
                    ->isRequired()
                        ->validate()
                            ->ifTrue(function($v){
                                return !empty($v);    
                            })
                            ->then(function($v){
                               return (boolean) $v; 
                            })
                    ->end()    
                    ->info('true or false')
                    ->example('true | false')
                ->end()
            ->end();
        
        return $rootNode;
    }
    
}
/* End of file */
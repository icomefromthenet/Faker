<?php
namespace Faker\Components\Engine\Common\Type;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Return null values
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class Null extends Type
{

    public function generate($rows, &$values = array())
    {
        return NULL;
    }
    
    /**
     * Generates the configuration tree builder.
     *
     */
    public function getConfigTreeBuilder()
    {

        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');
	
	return $treeBuilder;
    }
}
/* End of class */
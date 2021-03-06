<?php
namespace Faker\Components\Engine\Common\Type;

use Closure;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

/**
 * Closure Type
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class ClosureType extends Type
{
    
    
    /**
      *  @var Closure to execute 
      */
    protected $closure;
    
    
    public function setClosure(Closure $callable)
    {
        $this->closure = $callable;
    }
    
    
    public function getClosure()
    {
        return $this->closure;
    }
    
    //  -------------------------------------------------------------------------

    /**
     * Generate a value
     * 
     * @return string 
     */
    public function generate($rows,&$values = array(),$last = array())
    {
        return call_user_func($this->closure,$rows,$values,$last);
    }
    
    
    //  -------------------------------------------------------------------------

    /**
     * Generates the configuration tree builder.
     *
     */
    public function getConfigTreeExtension(NodeDefinition $rootNode)
    {
        return $rootNode;
    } 
    
    
    
}
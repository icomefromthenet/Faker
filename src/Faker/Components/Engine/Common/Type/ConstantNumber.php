<?php
namespace Faker\Components\Engine\Common\Type;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

/**
 * Const values for all php number primitives
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class ConstantNumber extends Type
{
    
    const INTTYPE      = 'integer';
    const STRINGTYPE   = 'string';
    const BOOLTYPE     = 'bool';
    const BOOLEANTYPE  = 'boolean';
    const FLOATTYPE    = 'float';
    const DOUBLETYPE   = 'double';
    
    
    protected $find_value;
    
    //----------------------------------------------------------
    /**
     * Geneates a constant value
     * 
     * @return string
     * @param interger $rows
     */
    public function generate($rows,&$values = array(),$last = array())
    {
        if($this->find_value == null) {
          
          $cast  = $this->getOption('type');
          $value = $this->getOption('value');
          
          switch($cast) {
           case self::BOOLTYPE: 
           case self::BOOLEANTYPE:
            throw new EngineException('Can not use constant for this primitive');
           break;
           case self::DOUBLETYPE:
            $this->find_value = (double) $value;
           break;
           case self::FLOATTYPE:
            $this->find_value = (float) $value;
           break;
           case self::INTTYPE:
            $this->find_value = (int) $value;
           break;
           default:
            $this->find_value = (string) $value;
          }
          
                
        }
        
        return $this->find_value;
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
                ->scalarNode('value')
                    ->isRequired()
                    ->info('The constant value to use')
                ->end()
                ->scalarNode('type')
                    ->info('Cast to use')
                    ->example('string|boolean|integer|float|double')
                    ->defaultValue('integer')
                    ->validate()
                        ->ifTrue(function($v){
                            
                            $valid_values = array(
                                \Faker\Components\Engine\Common\Type\ConstantNumber::INTTYPE,
                                \Faker\Components\Engine\Common\Type\ConstantNumber::STRINGTYPE,
                                \Faker\Components\Engine\Common\Type\ConstantNumber::BOOLTYPE,
                                \Faker\Components\Engine\Common\Type\ConstantNumber::BOOLEANTYPE,
                                \Faker\Components\Engine\Common\Type\ConstantNumber::FLOATTYPE,
                                \Faker\Components\Engine\Common\Type\ConstantNumber::DOUBLETYPE,
                            );
                            
                            return !in_array($v,$valid_values);  
                            
                        })
                        ->then(function($v) {
                            throw new EngineException('Constant::Type Option not in valid list');    
                        })
                    ->end()
                ->end()
            ->end();
        
        return $rootNode;
    }
    
}
/* End of class */
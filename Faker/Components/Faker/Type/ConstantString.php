<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class ConstantString extends Type
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
    public function generate($rows,$values = array())
    {
        if($this->find_value == null) {
          
          $cast  = $this->getOption('type');
          $value = $this->getOption('value');
          
          switch($cast) {
           case self::BOOLTYPE: 
           case self::BOOLEANTYPE:
            $this->find_value = (boolean) $value;
           break;
           case self::DOUBLETYPE:
            $this->find_value = (double) $value;
           break;
           case self::FLOATTYPE:
            $this->find_value = (float) $value;
           break;
           case self::INTTYPE:
            $this->find_value = (integer) $value;
           break;
           default:
            $this->find_value = (string) $value;
          }
          
                
        }
        
        return $this->find_value;
    }

    //  -------------------------------------------------------------------------

    public function toXml()
    {
       return '<datatype name="'.$this->getId().'"></datatype>' . PHP_EOL;
    }
 
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
                ->scalarNode('value')
                    ->isRequired()
                    ->info('The constant value to use')
                ->end()
                ->scalarNode('type')
                    ->info('Cast to use')
                    ->example('string|boolean|integer|float|double')
                    ->defaultValue('string')
                    ->validate()
                        ->ifTrue(function($v){
                            
                            $valid_values = array(
                                \Faker\Components\Faker\Type\ConstantString::INTTYPE,
                                \Faker\Components\Faker\Type\ConstantString::STRINGTYPE,
                                \Faker\Components\Faker\Type\ConstantString::BOOLTYPE,
                                \Faker\Components\Faker\Type\ConstantString::BOOLEANTYPE,
                                \Faker\Components\Faker\Type\ConstantString::FLOATTYPE,
                                \Faker\Components\Faker\Type\ConstantString::DOUBLETYPE,
                            );
                            
                            return !in_array($v,$valid_values);  
                            
                        })
                        ->then(function($v) {
                            throw new \Faker\Components\Faker\Exception('Constant::Type Option not in valid list');    
                        })
                    ->end()
                ->end()
            ->end();
    }
    
    //  -------------------------------------------------------------------------

    public function validate()
    {
        return true;
    }
    
    //  -------------------------------------------------------------------------
}

/* End of class */
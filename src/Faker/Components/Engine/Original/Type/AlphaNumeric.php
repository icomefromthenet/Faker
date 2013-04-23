<?php
namespace Faker\Components\Engine\Original\Type;

use Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Common\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder;

class AlphaNumeric extends Type
{


    const REPEAT_MAX = 'repeatMax';
    
    const REPEAT_MIN = 'repeatMin';
    
    const FORMAT     = 'format';


    //  -------------------------------------------------------------------------

    /**
     * Generate a value
     * 
     * @return string 
     */
    public function generate($rows,$values = array())
    {
        $format     = $this->getOption(self::FORMAT);
        $repeat_min = $this->getOption(self::REPEAT_MIN);
        $repeat_max = $this->getOption(self::REPEAT_MAX);     
        $value      = null;
        
        
        
        if($repeat_min === 1 && $repeat_max === 1) {
            # condition no-repeat 
            $value = $this->utilities->generateRandomAlphanumeric($format,$this->getGenerator(),$this->getLocale());
        }
        else {
            
            # condition repeat between x and y
            if($repeat_min === $repeat_max) {
                $repeat = $repeat_max;
            } else {
                $repeat = ceil($this->getGenerator()->generate($repeat_min,$repeat_max));
            }
            
            for($repeat; $repeat >= 0; $repeat--) {
                $value .= $this->utilities->generateRandomAlphanumeric($format,$this->getGenerator(),$this->getLocale());
            }
        }
        
        return $value;
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
                ->scalarNode(self::FORMAT)
                    ->info('Text format to use')
                    ->example('xxxxx ccccCC')
                ->end()
                ->scalarNode(self::REPEAT_MIN)
                    ->info('Minimum number of times to repeat the format')
                    ->example('1')
                    ->defaultValue(1)
                    ->validate()
                        ->ifTrue(function($x){
                            return !(is_integer($x) && $x >= 0);
                        
                        })
                        ->then(function($x){
                             throw new Faker\Components\Engine\Original\Exception('AlphaNumeric:: '.self::REPEAT_MIN .':: value must be an integer greater than or equal to zero');
                        })
                    ->end()
                ->end()
                ->scalarNode(self::REPEAT_MAX)
                    ->info('Maxium number of times to repear the format')
                    ->example('6')
                    ->defaultValue(1)
                    ->validate()
                        ->ifTrue(function($x){
                           return !(is_integer($x) && $x > 0);
                        
                        })
                        ->then(function($x){
                            throw new Faker\Components\Engine\Original\Exception('AlphaNumeric:: '.self::REPEAT_MAX .':: value must be an integer greater than zero');
                        
                        })
                    ->end()
                ->end()
            ->end();
            
    }
    
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        $repeat_min = $this->getOption(self::REPEAT_MIN);
        $repeat_max = $this->getOption(self::REPEAT_MAX);     
        
        if($repeat_min < 0 && $repeat_max === 0) {
            throw new FakerException('AlphaNumeric::Repeat range is not valid must be between 0 - x');
        }
        
        return true;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of file */
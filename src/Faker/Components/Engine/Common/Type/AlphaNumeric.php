<?php
namespace Faker\Components\Engine\Common\Type;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Composite\CompositeException;
use Faker\Components\Engine\Common\Utilities;


/**
 * AlphaNumeric Type
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
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
    public function generate($rows,&$values = array(),$last = array())
    {
        $format     = $this->getOption(self::FORMAT);
        $repeat_min = $this->getOption(self::REPEAT_MIN);
        $repeat_max = $this->getOption(self::REPEAT_MAX);     
        $value      = null;
        
        
        if($repeat_min === 1 && $repeat_max === 1) {
            # condition no-repeat 
            $value = $this->getUtilities()->generateRandomAlphanumeric($format,$this->getGenerator(),$this->getLocale());
        }
        else {
            
            # condition repeat between x and y
            if($repeat_min === $repeat_max) {
                $repeat = $repeat_max;
            } else {
                $repeat = ceil($this->getGenerator()->generate($repeat_min,$repeat_max));
            }
            
            for($repeat; $repeat >= 0; $repeat--) {
                $value .= $this->getUtilities()->generateRandomAlphanumeric($format,$this->getGenerator(),$this->getLocale());
            }
        }
        
        return $value;
    }
    
    
   
    //  -------------------------------------------------------------------------
    
    /**
     * Generates the configuration tree builder.
     *
     *  
     */
    public function getConfigTreeExtension(NodeDefinition $rootNode)
    {
        $rootNode
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
                            
                            $minText = \Faker\Components\Engine\Common\Type\AlphaNumeric::REPEAT_MIN;
                            
                            throw new InvalidConfigurationException('AlphaNumeric::'.$minText.' value must be an integer greater than or equal to zero');
                        })
                    ->end()
                ->end()
                ->scalarNode(self::REPEAT_MAX)
                    ->info('Maximum number of times to repear the format')
                    ->example('6')
                    ->defaultValue(1)
                    ->validate()
                        ->ifTrue(function($x){
                           return !(is_integer($x) && $x > 0);
                        
                        })
                        ->then(function($x){
                            $maxText = \Faker\Components\Engine\Common\Type\AlphaNumeric::REPEAT_MAX ;
                            throw new InvalidConfigurationException('AlphaNumeric::'.$maxText.' value must be an integer greater than zero');
                        })
                    ->end()
                ->end()
            ->end();
            
            
        return $rootNode;
            
    }
    
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        parent::validate();
        
        $repeat_min = $this->getOption(self::REPEAT_MIN);
        $repeat_max = $this->getOption(self::REPEAT_MAX);     
        
        if($repeat_min >  $repeat_max) {
            throw new EngineException('AlphaNumeric::Repeat range is not valid minimum is > maximum');
        }
        
        return true;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of file */
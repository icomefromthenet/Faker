<?php
namespace Faker\Generator;

use Faker\ExtensionInterface,
    Faker\Components\Faker\Exception as FakerException;

class GeneratorFactory implements ExtensionInterface
{
    
    
    /**
      *  @var string[] list of Generators
      *
      *  Each Generator must implement the Faker\GeneratorInterface
      */
    protected static $types = array(
        'srand'     => '\Faker\Generator\SrandRandom',
        'mersenne' => '\Faker\Generator\MersenneRandom',
        'simple'    => '\Faker\Generator\SimpleRandom',
    );
    
    public static function registerExtension($index,$namespace)
    {
        $index = strtolower($index);
        return self::$types[$index] = $namespace;
    }
    
    public static function registerExtensions(array $extension)
    {
        foreach($extension as $key => $ns) {
            self::registerExtension($key,$ns);
        }
    }
    
    //  ----------------------------------------------------------------------------
    
     /**
      *  Resolve a Dcotrine DataType Class
      *
      *  @param string the random generator type name
      *  @access public
      *  @return Faker\GeneratorInterface
      *  @throws Faker\Exception
      */
    public function create($type,$seed = null)
    {
        $type = strtolower($type);
        
        # check extension list
        
        if(isset(self::$types[$type]) === true) {
            # assign platform the full namespace
            if(class_exists(self::$types[$type]) === false) {
                throw new FakerException('Unknown Generator at::'.$type);    
            }
            
            $type = self::$types[$type];
            
        } else {
            throw new FakerException('Unknown Generator at::'.$type);
        }
       
        return new $type($seed);
    }
    
}
/* End of File */
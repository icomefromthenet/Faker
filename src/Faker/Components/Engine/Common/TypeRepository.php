<?php
namespace Faker\Components\Engine\Common;

use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\EngineException as FakerException;
use Faker\Components\Engine\Common\Type\TypeInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\ExtensionInterface;
use PHPStats\Generator\GeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
  *  Repository of types and their builders
  *  
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class TypeRepository implements ExtensionInterface
{

    /**
      *  @var 'name' => 'class'
      */
    static $types = array(
            'alphanumeric'    => '\\Faker\\Components\\Engine\\Common\\Builder\\AlphaNumericTypeDefinition',
            'closure'         => '\\Faker\\Components\\Engine\\Common\\Builder\\ClosureTypeDefinition',                 
            'nulltype'        => '\\Faker\\Components\\Engine\\Common\\Builder\\NullTypeDefinition',                 
            'autoincrement'   => '\\Faker\\Components\\Engine\\Common\\Builder\\AutoIncrementTypeDefinition',                 
            #'range'           => '\\Faker\\Components\\Engine\\Common\\Builder\\RangeTypeDefinition',
            'boolean'         => '\\Faker\\Components\\Engine\\Common\\Builder\\BooleanTypeDefinition',
            'constant_number' => '\\Faker\\Components\\Engine\\Common\\Builder\\ConstantNumberTypeDefinition',
            'constant_string' => '\\Faker\\Components\\Engine\\Common\\Builder\\ConstantStringTypeDefinition',
            'numeric'         => '\\Faker\\Components\\Engine\\Common\\Builder\\NumericTypeDefinition',
            #'text'            => '\\Faker\\Components\\Engine\\Common\\Builder\\Text',
            'date'            => '\\Faker\\Components\\Engine\\Common\\Builder\\DateTypeDefinition',
            'datetime'        => '\\Faker\\Components\\Engine\\Common\\Builder\\DateTypeDefinition',
            'email'           => '\\Faker\\Components\\Engine\\Common\\Builder\\EmailTypeDefinition',
            #'latlng'          => '\\Faker\\Components\\Engine\\Common\\Builder\\LatLng',
            #'unique_number'   => '\\Faker\\Components\\Engine\\Common\\Builder\\UniqueNumber',
            #'unique_string'   => '\\Faker\\Components\\Engine\\Common\\Builder\\UniqueString',
            'people'          => '\\Faker\\Components\\Engine\\Common\\Builder\\NamesTypeDefinition',
            'city'            => '\\Faker\\Components\\Engine\\Common\\Builder\\CitiesTypeDefinition',
            'country'         => '\\Faker\\Components\\Engine\\Common\\Builder\\CountryTypeDefinition',
            #'template'        => '\\Faker\\Components\\Engine\\Common\\Builder\\Template',
            #'regex'           => '\\Faker\\Components\\Engine\\Common\\Builder\\Regex',
        
    );

    /**
      *  Find a type by a given short name
      *
      *  @access public
      *  @return string a class name
      *  @param string $name the alias in lowercase
      */
    public function find($name)
    {
        $result = null;
        
        if(isset(self::$types[$name])) {
            $result = self::$types[$name];
        }
        
        return $result;
    }
    
    
    //  ----------------------------------------------------------------------------
    # Registration
    
    /**
      *  Register an new config or overrite and existing
      *
      *  @param string $key lowercase key
      *  @param string $ns the namespace
      *  @access public
      */    
    public static function registerExtension($key,$ns)
    {
        $key = strtolower((string)$key);
        self::$types[$key] = $ns;
    }
    
    /**
      *  Register an new config or overrite and existing
      *
      *  @param array $ext associate array with key and namespace as value
      *  @access public
      */
    public static function registerExtensions(array $ext)
    {
        foreach($ext as $key => $ns) {
            self::registerExtension($key,$ns);
        }
    }
    
    /**
      *  Clear the extension list
      *
      *  @access public
      *  @return null
      */
    public static function clearExtensions()
    {
        self::$types = array();
    }
    
}
/* End of File */
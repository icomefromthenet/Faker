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
            'nulltype'        => '\\Faker\\Components\\Engine\\Original\\Type\\Null',                 
            'autoincrement'   => '\\Faker\\Components\\Engine\\Original\\Type\\AutoIncrement',                 
            'range'           => '\\Faker\\Components\\Engine\\Original\\Type\\Range',
            'boolean'         => '\\Faker\\Components\\Engine\\Original\\Type\\BooleanType',
            'constant_number' => '\\Faker\\Components\\Engine\\Original\\Type\\ConstantNumber',
            'constant_string' => '\\Faker\\Components\\Engine\\Original\\Type\\ConstantString',
            'numeric'         => '\\Faker\\Components\\Engine\\Original\\Type\\Numeric',
            'text'            => '\\Faker\\Components\\Engine\\Original\\Type\\Text',
            'date'            => '\\Faker\\Components\\Engine\\Original\\Type\\Date',
            'datetime'        => '\\Faker\\Components\\Engine\\Original\\Type\\Datetime',
            'email'           => '\\Faker\\Components\\Engine\\Original\\Type\\Email',
            'latlng'          => '\\Faker\\Components\\Engine\\Original\\Type\\LatLng',
            'unique_number'   => '\\Faker\\Components\\Engine\\Original\\Type\\UniqueNumber',
            'unique_string'   => '\\Faker\\Components\\Engine\\Original\\Type\\UniqueString',
            'people'          => '\\Faker\\Components\\Engine\\Original\\Type\\Names',
            'city'            => '\\Faker\\Components\\Engine\\Original\\Type\\Cities',
            'country'         => '\\Faker\\Components\\Engine\\Original\\Type\\Country',
            'template'        => '\\Faker\\Components\\Engine\\Original\\Type\\Template',
            'regex'           => '\\Faker\\Components\\Engine\\Original\\Type\\Regex',
        
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
<?php
namespace Faker\Components\Faker;

use Faker\Components\Faker\Utilities,
    Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\TypeConfigInterface,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\ExtensionInterface,
    Faker\Generator\GeneratorInterface,
    Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
  *  Construct new DatatypeConfig objects under the namespace Faker\Components\Faker\Config
  *  these config objects are factories for their partner datatypes.
  */
class TypeFactory implements ExtensionInterface
{

    /**
      *  @var 'name' => 'class'
      */
    static $types = array(
            'alphanumeric'    => '\\Faker\\Components\\Faker\\Type\\AlphaNumeric',                 
            'null'            => '\\Faker\\Components\\Faker\\Type\\Null',                 
            'autoincrement'   => '\\Faker\\Components\\Faker\\Type\\AutoIncrement',                 
            'range'           => '\\Faker\\Components\\Faker\\Type\\Range',
            'boolean'         => '\\Faker\\Components\\Faker\\Type\\BooleanType',
            'constant_number' => '\\Faker\\Components\\Faker\\Type\\ConstantNumber',
            'constant_string' => '\\Faker\\Components\\Faker\\Type\\ConstantString',
            'numeric'         => '\\Faker\\Components\\Faker\\Type\\Numeric',
            'text'            => '\\Faker\\Components\\Faker\\Type\\Text',
            'date'            => '\\Faker\\Components\\Faker\\Type\\Date',
            'datetime'        => '\\Faker\\Components\\Faker\\Type\\Datetime',
            'email'           => '\\Faker\\Components\\Faker\\Type\\Email',
            'latlng'          => '\\Faker\\Components\\Faker\\Type\\LatLng',
            'unique_number'   => '\\Faker\\Components\\Faker\\Type\\UniqueNumber',
            'unique_string'   => '\\Faker\\Components\\Faker\\Type\\UniqueString',
            'people'          => '\\Faker\\Components\\Faker\\Type\\Names',
            'city'            => '\\Faker\\Components\\Faker\\Type\\Cities',
            'country'         => '\\Faker\\Components\\Faker\\Type\\Country',
        
    );

    /**
      *  @var  Faker\Components\Faker\Utilities
      */
    protected $util;
    
    /**
      *  @var EventDispatcherInterface 
      */
    protected $event;
    
    /**
      *  @var Faker\Generator\GeneratorInterface the random number generator 
      */
    protected $generator;
    
    //  -------------------------------------------------------------------------
    
    
    public function __construct(Utilities $util, EventDispatcherInterface $event,GeneratorInterface $generator)
    {
        $this->util = $util;
        $this->event = $event;
        
        # a default Generator
        $this->generator = $generator;
    }
    
    
    //  ----------------------------------------------------------------------------
    # Factory Method
    
    /**
      *  Create a new Type object
      *
      *  @param string lowercase name
      *  @return TypeConfigInterface
      */
    public function create($name, CompositeInterface $parent)
    {
        $name = strtolower($name);
        
        if(isset(self::$types[$name]) === false) {
            throw new FakerException('Type not found at::'.$name);
        }
     
        if(class_exists(self::$types[$name]) === false) {
            throw new FakerException('Class not found at::'.self::$types[$name]);
        }
        
        $type =  new self::$types[$name]($name,$parent,$this->event,$this->util,$this->generator);
    
        return $type;
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
    
    public static function clearExtensions()
    {
        self::$types = array();
    }
    
}
/* End of File */
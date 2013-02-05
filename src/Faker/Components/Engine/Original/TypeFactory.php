<?php
namespace Faker\Components\Engine\Original;

use Faker\Components\Engine\Original\Utilities,
    Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Original\TypeConfigInterface,
    Faker\Components\Engine\Original\Composite\CompositeInterface,
    Faker\ExtensionInterface,
    PHPStats\Generator\GeneratorInterface,
    Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
  *  Construct new DatatypeConfig objects under the namespace Faker\Components\Engine\Original\Config
  *  these config objects are factories for their partner datatypes.
  */
class TypeFactory implements ExtensionInterface
{

    /**
      *  @var 'name' => 'class'
      */
    static $types = array(
            'alphanumeric'    => '\\Faker\\Components\\Engine\\Original\\Type\\AlphaNumeric',                 
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
      *  @var  Faker\Components\Engine\Original\Utilities
      */
    protected $util;
    
    /**
      *  @var EventDispatcherInterface 
      */
    protected $event;
    
    /**
      *  @var PHPStats\Generator\GeneratorInterface the random number generator 
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
        
        $id = spl_object_hash($parent).'.'. $name;
        $type =  new self::$types[$name]($id,$parent,$this->event,$this->util,$this->generator);
    
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
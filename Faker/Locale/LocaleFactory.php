<?php
namespace Faker\Locale;

use Faker\ExtensionInterface,
    Faker\Text\StringFactoryInterface,
    Faker\Exception as FakerException;


/**
  *  Locale Factory, will create locale objects and cache them (flightwight)
  *
  *   @since 1.0.3
  *   @author Lewis Dyer <getintouch@icomefromthenet.com>
  */
class LocaleFactory implements ExtensionInterface
{
    
    /**
      *  @var StringFactoryInterface the string factory 
      */
    protected $string_factory;
    
    /**
      *  @var string[] list of Generators
      *
      *  Each Generator must implement the Faker\GeneratorInterface
      */
    protected static $types = array(
        'en'        => '\Faker\Locale\EnglishLocale',
    );
    
    //  ----------------------------------------------------------------------------
    
    
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
    
    public static function hasExtension($ext)
    {
        return isset(self::$types[$ext]);
    }
    
    //  ----------------------------------------------------------------------------
    
    /**
      *  Class Constructor
      *
      *  @access public
      *  @param SimpleTextInterface $string_factory the string factory
      *  @return void
      */
    public function __construct(StringFactoryInterface $string_factory)
    {
        $this->string_factory = $string_factory;
        
    }
    
    //  ----------------------------------------------------------------------------
    
     /**
      *  Create a locale object
      *
      *  @param string the name of the locale
      *  @access public
      *  @return Faker\GeneratorInterface
      *  @throws Faker\Exception
      */
    public function create($type)
    {
        $type = strtolower($type);
        
        # check extension list
        
        if(isset(self::$types[$type]) === true) {
            
            if(is_object(self::$types[$type]) === false) {
            
                if(class_exists(self::$types[$type]) === false) {
                    throw new FakerException('Unknown Locale at::'.$type);    
                }
            
                $newtype = self::$types[$type];
                
                self::$types[$type] = new $newtype($this->string_factory);
            }
            
        } else {
            throw new FakerException('Unknown Locale at::'.$type);
        }
       
        return self::$types[$type];
    }
    
    //  ----------------------------------------------------------------------------
    
}
/* End of File */
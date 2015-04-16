<?php
namespace Faker\Components\Engine\Common\Datasource;

use Faker\ExtensionInterface;
use Faker\Components\Engine\EngineException as FakerException;


/**
  *  Repository of Builders for Datasource's
  *  
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.5
  */
class DatasourceRepository implements ExtensionInterface
{

    /**
      *  @var 'name' => 'class'
      */
    static $types = array(
            'filesource'    => '\\Faker\\Components\\Engine\\Common\\Datasource\\FileDatasourceDefinition',
            
        
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
<?php
namespace Faker\Components\Engine\Common\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform as Platform;

use Faker\ExtensionInterface;
use Faker\Components\Writer\Manager as WriterManager;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Visitor\DBALGathererVisitor;


/*
 * Factory for creating formatters 
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */
class FormatterFactory implements ExtensionInterface
{
    
    /**
      *  @var array[] of namespaces 
      */
    protected static $formatters = array(
        'sql'     => 'Faker\\Components\\Engine\\Common\\Formatter\\Sql',                
        'phpunit' => 'Faker\\Components\\Engine\\Common\\Formatter\\Phpunit'
    );
    
    
    /**
      *  @var Faker\Components\Writer\Manager 
      */
    protected $writer;

    /**
      *  @var  Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $event;
    
    /**
      *  @var  Faker\Components\Engine\Common\Visitor\DBALGathererVisitor
      */
    protected $visitor;
    
    /**
     * Split the class into namespace and class name to do match
     * 
     * @return array('namespace' => '', 'classname' => '' )
     * @param string a FQN of a class
     */ 
    protected static function parseClassname ($name)
    {
      return array(
            'namespace' => array_slice(explode('\\', $name), 0, -1),
            'classname' => join('', array_slice(explode('\\', $name), -1)),
      );
    }
    
    /**
     * Check if 2 parsed class names are a match.
     * 
     * @param array $parsedClassNameA The result of self::parseClassname
     * @param array $parsedClassNameB The result of self::parseClassname
     * @return boolean true if match
     */ 
    protected static function isMatch($parsedClassNameA,$parsedClassNameB)
    {
        $notMatch = false;
        
        foreach($parsedClassNameA['namespace'] as $index => $value) {
            if(0 !== strcmp($parsedClassNameA['namespace'][$index],$value)) {
                $notMatch = true;        
                break;
            }
        }
        
        return false === $notMatch 
                && count($parsedClassNameA['namespace']) === count($parsedClassNameB['namespace']) 
                && strcmp($parsedClassNameA['classname'],$parsedClassNameB['classname']) === 0;
    }
        
    /**
      * Class Constructor
      *
      * @param EventDispatcherInterface $event
      * @param WriterManager $writer
      * @param Connection $connection doctine db object
      */
    public function __construct(EventDispatcherInterface $event, WriterManager $writer, DBALGathererVisitor $visitor)
    {
        $this->event   = $event;
        $this->writer  = $writer;
        $this->visitor = $visitor;
    }
 
 
    public function create($formatter, Platform $platform,$options = array())
    {
        $formatter = strtolower($formatter);

        if(isset(self::$formatters[$formatter]) === false) {
            throw new EngineException('Formatter does not exist at::'.$formatter);
        }
       
        $class = new self::$formatters[$formatter]($this->event,
                                                   $this->writer->getWriter($platform->getName(),$formatter),
                                                   $platform,
                                                   $this->visitor,
                                                   $options
                                                   );
       
        # register this formatter as a subscriber 
        $this->event->addSubscriber($class); 
        
        return $class;
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Register an new formatter or overrite and existing
      *
      *  @param string $key lowercase key
      *  @param string $ns the namespace
      *  @access public
      */    
    public static function registerExtension($key,$ns)
    {
        $key = strtolower((string)$key);
        self::$formatters[$key] = $ns;
    }
    
    /**
      *  Register an new formatter or overrite and existing
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
     * Does a reverse lookup with actual class name
     * 
     * @return string id used to identify this formatter in factory
     * @param string $formatterClass the class instance to reverse lookup
     */ 
    public static function reverseLookup($formatterClassName) {
        $name = false;
        
        foreach(self::$formatters as $fkey =>$formatter) {
            $parsedA  = self::parseClassname($formatter);
            $parsedB  = self::parseClassname($formatterClassName);
        
            if(true === self::isMatch($parsedA,$parsedB)) {
                $name = $fkey;
                break;
            }
        }
        
        return $name;
    }
    
    
}
/* End of File */
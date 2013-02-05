<?php
namespace Faker\Components\Engine\Original\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform as Platform;
use Faker\Components\Writer\Manager as WriterManager;
use Faker\Components\Engine\Original\Exception as FakerException;
use Faker\ExtensionInterface;

class FormatterFactory implements ExtensionInterface
{
    
    /**
      *  @var array[] of namespaces 
      */
    protected static $formatters = array(
        'sql'     => 'Faker\\Components\\Engine\\Original\\Formatter\\Sql',                
        'phpunit' => 'Faker\\Components\\Engine\\Original\\Formatter\\Phpunit'
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
      * Class Constructor
      *
      * @param EventDispatcherInterface $event
      * @param WriterManager $writer
      * @param Connection $connection doctine db object
      */
    public function __construct(EventDispatcherInterface $event, WriterManager $writer)
    {
        $this->event = $event;
        $this->writer = $writer;
    }
 
 
    public function create($formatter, Platform $platform,$options = array())
    {
        $formatter = strtolower($formatter);
        
        if(isset(self::$formatters[$formatter]) === false) {
            throw new FakerException('Formatter does not exist at::'.$formatter);
        }
       
        $class = new self::$formatters[$formatter]($this->event,
                                                   $this->writer->getWriter($platform->getName(),$formatter),
                                                   $platform,
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
    
}
/* End of File */
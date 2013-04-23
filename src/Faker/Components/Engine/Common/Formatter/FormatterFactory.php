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
    
}
/* End of File */
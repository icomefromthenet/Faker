<?php
namespace Faker\Components\Faker\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Doctrine\DBAL\Platforms\AbstractPlatform,
    Faker\Components\Writer\WriterInterface,
    Faker\Components\Faker\Exception as FakerException;

class Phpunit extends BaseFormatter implements FormatterInterface
{
    
    //  -------------------------------------------------------------------------
    # Constructor
    
    /**
      *  class constructor
      *
      *  @param EventDispatcherInterface $event
      *  @param WriterInterface $writer
      *  @param AbstractPlatform $platform the doctine platform class
      *  @param array mixed[] options
      */
    public function __construct(EventDispatcherInterface $event, WriterInterface $writer, AbstractPlatform $platform, $options = array())
    {
        $this->setEventDispatcher($event);
        $this->setWriter($writer);
        $this->setPlatform($platform);
        $this->options = $options;
       
    }
    
    public function getName()
    {
        return 'phpunit';
    }
    
    public function getOuputFileFormat()
    {
        return '{prefix}_{body}_{suffix}.{ext}';
    }
    
    /**
      *  Defines the default output encoding
      *
      *  @return string the out encoding
      *  @access public
      */
    public function getDefaultOutEncoding()
    {
        return 'UTF-8';
    }
    
    
    //  -------------------------------------------------------------------------
    # Format Events
    
    
    /**
      *  Handles Event FormatEvents::onSchemaStart
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaStart(GenerateEvent $event)
    {
        # set the schema prefix on writter
        $this->writer->getStream()->getSequence()->setPrefix(strtolower($event->getType()->getOption('name')));
        $this->writer->getStream()->getSequence()->setBody('fixture');
        $this->writer->getStream()->getSequence()->setSuffix($this->platform->getName());
        $this->writer->getStream()->getSequence()->setExtension('xml');
        
        
        $now = new \DateTime();
        $server_name = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost'; 
        
        $this->writer->getStream()->getHeaderTemplate()->setData(array(
                                        'faker_version' => FAKER_VERSION,
                                        'host'          => $server_name,
                                        'datetime'      => $now->format(DATE_W3C),
                                        'phpversion'    => PHP_VERSION,
                                        'schema'        => $event->getType()->getOption('name'),
                                        'platform'      => $this->platform->getName(),
                                        ));
        # start writing here
    
        $this->writer->write('<dataset>' . PHP_EOL);
        
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onSchemaEnd
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaEnd(GenerateEvent $event)
    {
        $this->writer->write('</dataset>' . PHP_EOL);
        
        # we only flush at the end to keep all lines in single file
        $this->writer->flush();
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableStart
      *
      *  @param GenerateEvent $event
      */
    public function onTableStart(GenerateEvent $event)
    {
        # build a column map
        $map = array();

        foreach($event->getType()->getChildren() as $column) {
            $map[$column->getOption('name')] = $column->getColumnType();
        }
       
        $this->column_map = $map;
        
        # write table tag        
        $this->writer->write(sprintf('<table name="%s">'. PHP_EOL,$event->getType()->getOption('name')));
    
         # fetch the columns for each table   
         foreach($event->getType()->getChildren() as $column) {
            $this->writer->write('<column>'.trim($column->getOption('name')).'</column>' .PHP_EOL);
         }
            
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableEnd
      *
      *  @param GenerateEvent $event
      */
    public function onTableEnd(GenerateEvent $event)
    {
        $this->writer->write('</table>' . PHP_EOL);
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowStart
      *
      *  @param GenerateEvent $event
      */
    public function onRowStart(GenerateEvent $event)
    {
        $this->writer->write('<row>'.PHP_EOL);
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowEnd
      *
      *  @param GenerateEvent $event
      */
    public function onRowEnd(GenerateEvent $event)
    {
        $this->writer->write('</row>'.PHP_EOL);
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onColumnStart
      *
      *  @param GenerateEvent $event
      */
    public function onColumnStart(GenerateEvent $event)
    {
        
    }
    
    /**
      *  Handles Event FormatEvents::onColumnGenerate
      *
      *  @param GenerateEvent $event
      */
    public function onColumnGenerate(GenerateEvent $event)
    {
       
    }
    
    
    /**
      *  Handles Event FormatEvents::onColumnEnd
      *
      *  @param GenerateEvent $event
      */
    public function onColumnEnd(GenerateEvent $event)
    {
        $values = $event->getValues();
        $value = $this->processColumnWithMap($event->getType()->getOption('name'),$values[$event->getType()->getOption('name')]);
        
        if($value !== null) {
            $this->writer->write('<value>');
            $this->writer->write(htmlentities($value));
            $this->writer->write('</value>'.PHP_EOL);
        } else {
            $this->writer->write('<null />');            
        } 
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Convert the formatter to xml representation
      *
      *  @return string the xml rep
      *  @access public
      */  
    public function toXml()
    {
        return '<writer platform="'.$this->getPlatform()->getName().'" format="'.$this->getName().'" />';
    }

    //  -------------------------------------------------------------------------
    
    /**
      *  Overrides the base class merge to configure the writer
      *  after the definitions are merged.
      */
    public function merge()
    {
        parent::merge();
        
        # change the format on the writer to remove the seq number
        # since we are using a single file format

        $this->getWriter()->getStream()->getLimit()->changeLimit(null);
        $this->writer->getStream()->getSequence()->setFormat($this->getOption(self::CONFIG_OPTION_OUT_FILE_FORMAT));
        
    }
    
    
    /**
      *  Will fetch config extensions used in child formatters that
      *  want to extends the default config tree
      *
      *  @return ArrayNodeDefinition
      *  @access public
      */
    public function getConfigExtension(ArrayNodeDefinition $rootNode)
    {
	return $rootNode;
    }
    
    //  ----------------------------------------------------------------------------
    
}
/* End of File */

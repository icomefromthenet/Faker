<?php
namespace Faker\Components\Engine\Common\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Doctrine\DBAL\Platforms\AbstractPlatform;

use Faker\Components\Engine\EngineException;
use Faker\Components\Writer\WriterInterface;
use Faker\Components\Engine\Common\Formatter\FormatEvents;

/*
 * Formats output into phpunit XML DataFixture Format
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */
class Phpunit extends BaseFormatter implements FormatterInterface
{
    
    
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
        $writer       = $this->getWriter();
        $stream       = $writer->getStream();
        $sequence     = $stream->getSequence();
        $platform     = $this->getPlatform();
        $schemaName   = $event->getNode()->getId();
        
        # set the schema prefix on writter
        $sequence->setPrefix(strtolower($schemaName));
        $sequence->setBody('fixture');
        $sequence->setSuffix($platform->getName());
        $sequence->setExtension('xml');
        
        
        $now         = new \DateTime();
        $server_name = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost'; 
        
        $stream->getHeaderTemplate()->setData(array(
                                        'faker_version' => FAKER_VERSION,
                                        'host'          => $server_name,
                                        'datetime'      => $now->format(DATE_W3C),
                                        'phpversion'    => PHP_VERSION,
                                        'schema'        => $schemaName,
                                        'platform'      => $platform->getName(),
                                        ));
        # start writing here
    
        $writer->write('<dataset>' . PHP_EOL);
        
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onSchemaEnd
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaEnd(GenerateEvent $event)
    {
        $writer = $this->getWriter();
        
        $writer->write('</dataset>' . PHP_EOL);
        
        # we only flush at the end to keep all lines in single file
        $writer->flush();
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableStart
      *
      *  @param GenerateEvent $event
      */
    public function onTableStart(GenerateEvent $event)
    {
        $table    = $event->getNode();
        $tableId  = $table->getId();
        $children = $table->getChildren();
        $writer   = $this->getWriter();
                
        # write table tag        
        $writer->write(sprintf('<table name="%s">'. PHP_EOL,$tableId));
    
         # fetch the columns for each table   
         foreach($children as $column) {
            $this->writer->write('<column>'.trim($column->getId()).'</column>' .PHP_EOL);
         }
            
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableEnd
      *
      *  @param GenerateEvent $event
      */
    public function onTableEnd(GenerateEvent $event)
    {
        $this->getWriter()->write('</table>' . PHP_EOL);
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowStart
      *
      *  @param GenerateEvent $event
      */
    public function onRowStart(GenerateEvent $event)
    {
        $this->getWriter()->write('<row>'.PHP_EOL);
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowEnd
      *
      *  @param GenerateEvent $event
      */
    public function onRowEnd(GenerateEvent $event)
    {
        $this->getWriter()->write('</row>'.PHP_EOL);
        
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
        $values   = $event->getValues();
        $columnId = $event->getNode()->getId();
        $writer   = $this->getWriter();
        $platform = $this->platform;
        
        $value = $this->valueConverter->convertValue($columnId,$platform,$values[$columnId]);
        
        if($value !== null) {
            $writer->write('<value>' . \htmlentities($value) .'</value>'.PHP_EOL);
        } else {
            $writer->write('<null />');            
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
    
    public function validate()
    {
        # run merge and validate of the config options
        parent::validate();
        
        # change the format on the writer to remove the seq number
        # since we are using a single file format
        $writer = $this->getWriter();
        $stream = $writer->getStream();
        
        # need set big max or file will split
        $stream->getLimit()->changeLimit(null);
        $stream->getSequence()->setFormat($this->getOption(self::CONFIG_OPTION_OUT_FILE_FORMAT));
        
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

<?php
namespace Faker\Components\Engine\Common\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Doctrine\DBAL\Platforms\AbstractPlatform;

use Faker\Components\Writer\WriterInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Formatter\FormatEvents;

/*
 * Formats output into SQL Create Statements 
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.0
 */
class Sql extends BaseFormatter implements FormatterInterface
{
    
    const MAX_LINES = 1000;
    
    const SINGLE_FILE_MODE = false;
    
    const CONFIG_OPTION_MAX_LINES = 'maxLines';
    
    const CONFIG_OPTION_SINGLE_FILE_MODE = 'singleFileMode';
    
   
    
    public function getName()
    {
        return 'sql';
    }
    
    public function getOuputFileFormat()
    {
        return '{seq}_{prefix}_{body}_{suffix}.{ext}';
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
        $nodeId       = $event->getNode()->getId();
        $writer       = $this->getWriter();
        $stream       = $writer->getStream();
        $sequence     = $stream->getSequence();
        $encoder      = $stream->getEncoder();
        $platformName = $this->getPlatform()->getName();
        $outEncoding  = $this->getOption(self::CONFIG_OPTION_OUT_ENCODING);
        $now          = new \DateTime();
        $server_name  = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost'; 
        
        
        # set the schema prefix on writter
        $sequence->setPrefix(strtolower($nodeId));
        $sequence->setSuffix($platformName);
        $sequence->setExtension('sql');
        $encoder->setOutEncoding($outEncoding);
        
        $stream->getHeaderTemplate()->setData(array(
                                        'faker_version' => FAKER_VERSION,
                                        'host'          => $server_name,
                                        'datetime'      => $now->format(DATE_W3C),
                                        'phpversion'    => PHP_VERSION,
                                        'schema'        => $nodeId,
                                        'platform'      => $platformName,
                                        ));
    }
    
    
    /**
      *  Handles Event FormatEvents::onSchemaEnd
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaEnd(GenerateEvent $event)
    {
        $this->getWriter()->flush();
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableStart
      *
      *  @param GenerateEvent $event
      */
    public function onTableStart(GenerateEvent $event)
    {
       $writer         = $this->getWriter();
       $stream         = $writer->getStream();
       $sequence       = $stream->getSequence();
       $tableName      = $event->getNode()->getId();
       $parentNodeName = $event->getNode()->getParent()->getId();
       
       # set the prefix on the writer for table 
       $sequence->setBody(strtolower($tableName));
       
       $writer->write(PHP_EOL);
       $writer->write(PHP_EOL);
       $writer->write('--'.PHP_EOL);
       $writer->write('-- Table: '.$tableName.PHP_EOL);
       $writer->write('--'.PHP_EOL);
       $writer->write(PHP_EOL);
       $writer->write(PHP_EOL);
       
       $writer->write('USE '.$parentNodeName.';');        
       $writer->write(PHP_EOL);
       $writer->write(PHP_EOL);

    }
    
    
    /**
      *  Handles Event FormatEvents::onTableEnd
      *
      *  @param GenerateEvent $event
      */
    public function onTableEnd(GenerateEvent $event)
    {
       $writer       = $this->getWriter();
       $nodeName     = $event->getNode()->getId();
       $splitOnTable = $this->getOption(self::CONFIG_OPTION_SPLIT_ON_TABLE);
       
       $writer->write(PHP_EOL);
       $writer->write(PHP_EOL);
       $writer->write('--'.PHP_EOL);
       $writer->write('-- Finished Table: '.$nodeName.PHP_EOL);
       $writer->write('--'.PHP_EOL);
       $writer->write(PHP_EOL);
       $writer->write(PHP_EOL);
       
       # flush the writer for next table
       if( $splitOnTable === true) {
            $writer->flush(); 
       }
       
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowStart
      *
      *  @param GenerateEvent $event
      */
    public function onRowStart(GenerateEvent $event)
    {
        return null;
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowEnd
      *
      *  @param GenerateEvent $event
      */
    public function onRowEnd(GenerateEvent $event)
    {
        # iterate over the values and convert run them through the column map
        $values   = $event->getValues();
        $platform = $this->getPlatform();
        $writer   = $this->getWriter();
        $table    = $event->getNode()->getId();
        $q       = $platform->getIdentifierQuoteCharacter();
        
        foreach($values as $key => &$value) {
            $value = $this->valueConverter->convertValue($key,$platform,$value);
        }
        
        # build insert statement 
        # column names add quotes to them
        
        $column_keys = array_map(function($value) use ($q){
              return $q.$value.$q;
        },array_keys($values));
        
        
        $column_values = array_map(function($value){
            
            if(is_string($value)) {
                $value = "'" . str_replace("'","''",$value) . "'";
            }
            
            if(is_null($value) === true) {
                $value = 'NULL';
            }
              
            return $value;
        }, array_values($values));
        
        if(count($column_keys) !== count($column_values)) {
            throw new EngineException('Keys do not have enough values');
        }
        
        $stm = 'INSERT INTO '.$q. $table .$q.' (' .implode(',',$column_keys). ') VALUES ('. implode(',',$column_values) .');'. PHP_EOL;

        unset($values);
        unset($column_keys);
        unset($column_values);
        
        $writer->write($stm);
        
        return $stm;
        
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
        
    }
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        # run merge and validate of the config options
        parent::validate();
        
        $writer         = $this->getWriter();
        $limit          = $writer->getStream()->getLimit();
        $sequence       = $writer->getStream()->getSequence();
        $singleFileMode = $this->getOption(self::CONFIG_OPTION_SINGLE_FILE_MODE);
        $outFileFormat  = $this->getOption(self::CONFIG_OPTION_OUT_FILE_FORMAT);
        $maxLines       = $this->getOption(self::CONFIG_OPTION_MAX_LINES);
                
        if(!$writer instanceof WriterInterface) {
            throw new EngineException('Writter not been set can not finish merging config');
        }
        
        if( $singleFileMode === true) {
            
            # reverse the split on table and remove line limit to keep single file mode. 
            $this->setOption(self::CONFIG_OPTION_SPLIT_ON_TABLE,false);
            $this->setOption(self::CONFIG_OPTION_MAX_LINES,null);
            $limit->changeLimit(null);
        }
        else {
            # set the maxLines
            $limit->changeLimit($maxLines);
        }
        
        # set output format
        $sequence->setFormat($outFileFormat);
        
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
	# overridden the splitOnTable to default to true.
        
        $rootNode->children()
                    ->booleanNode(self::CONFIG_OPTION_SPLIT_ON_TABLE)
                        ->treatNullLike(true)
                        ->defaultValue(true)
                        ->info('Start a new file when a table has finished generating')
                    ->end()
                    ->scalarNode(self::CONFIG_OPTION_MAX_LINES)
                        ->treatNullLike(self::MAX_LINES)
                        ->defaultValue(self::MAX_LINES)
                        ->validate()
                            ->ifTrue(function($v){
                                return ! is_integer($v);                                
                            })
                            ->thenInvalid('maxLines must be an integer')
                        ->end()
                    ->end()
                    ->booleanNode(self::CONFIG_OPTION_SINGLE_FILE_MODE)
                        ->treatNullLike(self::SINGLE_FILE_MODE)
                        ->defaultValue(self::SINGLE_FILE_MODE)
                        ->info('Generate into a sinlge file not splitting on new table or maxlines')
                    ->end()
                 ->end();
        
        return $rootNode;
    }
    
    //  ----------------------------------------------------------------------------
}
/* End of File */
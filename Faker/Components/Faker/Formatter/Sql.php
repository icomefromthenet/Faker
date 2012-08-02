<?php
namespace Faker\Components\Faker\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Doctrine\DBAL\Platforms\AbstractPlatform,
    Faker\Components\Writer\WriterInterface,
    Faker\Components\Faker\Exception as FakerException;

class Sql extends BaseFormatter implements FormatterInterface
{
    
    const MAX_LINES = 1000;
    
    const SINGLE_FILE_MODE = false;
    
    const CONFIG_OPTION_MAX_LINES = 'maxLines';
    
    const CONFIG_OPTION_SINGLE_FILE_MODE = 'singleFileMode';
    
    //  -------------------------------------------------------------------------
    # Constructor
    
    /**
      *  class constructor
      *
      *  @param EventDispatcherInterface $event
      *  @param WriterInterface $writer
      *  @param AbstractPlatform $platform the doctine platform class
      *  @param mixed[] array of options
      */
    public function __construct(EventDispatcherInterface $event, WriterInterface $writer, AbstractPlatform $platform,$options = array())
    {
        $this->setEventDispatcher($event);
        $this->setWriter($writer);
        $this->setPlatform($platform);
        $this->options = $options;
    }
    
    public function getName()
    {
        return 'sql';
    }
    
    public function getOuputFileFormat()
    {
        return '{prefix}_{body}_{suffix}_{seq}.{ext}';
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
        $this->writer->getStream()->getSequence()->setSuffix($this->platform->getName());
        $this->writer->getStream()->getSequence()->setExtension('sql');
        $this->writer->getStream()->getEncoder()->setOutEncoding($this->getOption(self::CONFIG_OPTION_OUT_ENCODING));
        
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
    }
    
    
    /**
      *  Handles Event FormatEvents::onSchemaEnd
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaEnd(GenerateEvent $event)
    {
        $this->writer->flush();
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableStart
      *
      *  @param GenerateEvent $event
      */
    public function onTableStart(GenerateEvent $event)
    {
       
       # set the prefix on the writer for table 
       $this->writer->getStream()->getSequence()->setBody(strtolower($event->getType()->getOption('name')));
       
       # build a column map
       $map = array();

       foreach($event->getType()->getChildren() as $column) {
            $map[$column->getOption('name')] = $column->getColumnType();
       }
       
       $this->column_map = $map;
       
       $this->writer->write(PHP_EOL);
       $this->writer->write(PHP_EOL);
       $this->writer->write('--'.PHP_EOL);
       $this->writer->write('-- Table: '.$event->getType()->getOption('name').PHP_EOL);
       $this->writer->write('--'.PHP_EOL);
       $this->writer->write(PHP_EOL);
       $this->writer->write(PHP_EOL);
       
       $this->writer->write('USE '.$event->getType()->getParent()->getOption('name').';');        
       $this->writer->write(PHP_EOL);
       $this->writer->write(PHP_EOL);

    }
    
    
    /**
      *  Handles Event FormatEvents::onTableEnd
      *
      *  @param GenerateEvent $event
      */
    public function onTableEnd(GenerateEvent $event)
    {
       
       # unset the column map for next table
       $this->column_map = null;
       
       $this->writer->write(PHP_EOL);
       $this->writer->write(PHP_EOL);
       $this->writer->write('--'.PHP_EOL);
       $this->writer->write('-- Finished Table: '.$event->getType()->getOption('name').PHP_EOL);
       $this->writer->write('--'.PHP_EOL);
       $this->writer->write(PHP_EOL);
       $this->writer->write(PHP_EOL);
       
       # flush the writer for next table
       if($this->getOption(self::CONFIG_OPTION_SPLIT_ON_TABLE) === true) {
            $this->writer->flush(); 
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
        $map    = $this->getColumnMap();
        $values = $event->getValues();
        
        foreach($values as $key => &$value) {
            $value = $this->processColumnWithMap($key,$value);
        }
        
        # build insert statement 
        
        $q      = $this->platform->getIdentifierQuoteCharacter();
        $table  = $event->getType()->getOption('name');
        
        # column names add quotes to them
        
        $column_keys = array_map(function($value) use ($q){
              return $q.$value.$q;
        },array_keys($values));
        
        
        $column_values = array_map(function($value){
            
            if(is_string($value)) {
                $value = "'" . str_replace("'","''",$value) . "'";
            }
              
            return $value;
        }, array_values($values));
        
        if(count($column_keys) !== count($column_values)) {
            throw new FakerException('Keys do not have enough values');
        }
        
        $stm = 'INSERT INTO '.$q. $table .$q.' (' .implode(',',$column_keys). ') VALUES ('. implode(',',$column_values) .');'.PHP_EOL;

        unset($values);
        unset($column_keys);
        unset($column_values);
        
        $this->writer->write($stm);
        
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
    
    /**
      *  Convert the formatter to xml representation
      *
      *  @return string the xml rep
      *  @access public
      */  
    public function toXml()
    {
        return '<writer platform="'.$this->getPlatform()->getName().'" format="'.$this->getName().'" />' . PHP_EOL;
    }

    //  -------------------------------------------------------------------------
    
    /**
      *  Overrides the base class merge to configure the writer
      *  after the definitions are merged.
      */
    public function merge()
    {
        parent::merge();
        
        if(! $this->writer instanceof WriterInterface) {
            throw new FakerException('Writter not been set can not finish merging config');
        }
        
        if($this->getOption(self::CONFIG_OPTION_SINGLE_FILE_MODE) === true) {
            
            # reverse the split on table and remove line limit to keep single file mode. 
            $this->setOption(self::CONFIG_OPTION_SPLIT_ON_TABLE,false);
            $this->setOption(self::CONFIG_OPTION_MAX_LINES,null);
            $this->getWriter()->getStream()->getLimit()->changeLimit(null);
        }
        else {
            # set the maxLines
            $this->getWriter()->getStream()->getLimit()->changeLimit($this->getOption(self::CONFIG_OPTION_MAX_LINES));
        }
        
        # set output format
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
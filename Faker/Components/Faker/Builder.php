<?php
namespace Faker\Components\Faker;

use Faker\Components\Faker\Composite\Column,
    Faker\Components\Faker\Composite\Schema,
    Faker\Components\Faker\Composite\Table,
    Faker\Components\Faker\Composite\Alternate,
    Faker\Components\Faker\Composite\Pick,
    Faker\Components\Faker\Composite\Random,
    Faker\Components\Faker\Composite\Swap,
    Faker\Components\Faker\Composite\When,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Composite\SelectorInterface,
    Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\TypeInterface,
    Faker\PlatformFactory,
    Faker\ColumnTypeFactory,
    Faker\Components\Faker\Formatter\FormatterFactory,
    Faker\Components\Faker\Formatter\FormatterInterface,
    Faker\Components\Faker\TypeFactory,
    Faker\Components\Writer\WriterInterface,
    Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Builder
{
    
    /**
      *  @var Faker\Components\Faker\Composite\CompositeInterface; 
      */
    protected $head;
    
    /**
      *  @var Faker\Components\Faker\Composite\Schema 
      */
    protected $current_schema;
   
   /**
      *  @var Faker\Components\Faker\Composite\Table 
      */
    protected $current_table;
    
    /**
      *  @var Faker\Components\Faker\Composite\Column 
      */
    protected $current_column;
    
    /**
      *  @var  Faker\PlatformFactory
      */
    protected $platform_factory;
    
    /**
      *  @var Faker\ColumnTypeFactory 
      */
    protected $column_factory;
    
    /**
      *  @var Faker\Components\Faker\TypeFactory 
      */
    protected $type_factory;
    
    /**
      * @var Faker\Components\Faker\Formatter\FormatterFactory   
      */
    protected $formatter_factory;
    
    /**
      *  @var FormatterInterface[] the assigned writers 
      */
    protected $formatters = array();
    
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */
    protected $event;

    //  -------------------------------------------------------------------------

    
    public function __construct(EventDispatcherInterface $event,PlatformFactory $platform, ColumnTypeFactory $column, TypeFactory $type,FormatterFactory $formatter)
    {
        $this->event = $event;
        $this->platform_factory = $platform;
        $this->column_factory  = $column;
        $this->formatter_factory = $formatter;
        $this->type_factory = $type;
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function addWriter($platform,$formatter)
    {
        # instance a platform
        
        $platform_instance = $this->platform_factory->create($platform);
        
        $this->formatters[] = $this->formatter_factory
                                   ->create($formatter,$platform_instance); 
        
        return $this;
    }
    
    //  -------------------------------------------------------------------------

        
    public function addSchema($name,$options)
    {
        # merge options with default
        $options = array_merge(array(
                    'locale' => null
                    ),$options);
        
        # check if schema already set as we can have only one
        
        if($this->current_schema !== null) {
            throw new FakerException('Scheam already added only have one');
        }
       
        # validate the name for empty string
        
        if(empty($name)) {
            throw new FakerException('Schema must have a name');
        }
       
        # create the new schema
        
        $this->current_schema = new Schema($name,null,$this->event,array('locale' => $options['locale']));
        
        # assign schema as our head
        
        $this->head = $this->current_schema;
        
        return $this;
    }

    //  -------------------------------------------------------------------------

    
    public function addTable($name,$options)
    {
        
        # check if schema exist
        
        if(!$this->head instanceof Schema) {
            throw new FakerException('Must add a scheam first before adding a table');
        }
    
        # validate the name for empty string
        
        if(empty($name)) {
            throw new FakerException('Table must have a name');
        }
        
        if(isset($options['generate']) === false) {
            throw new FakerException('Table requires rows to generate');
        }
        
        
        # merge options with default
        $options = array_merge(array(
                    'locale' => $this->head->getOption('locale')
                    ),$options);
        
        
        # create the new table
        
        $table = new Table($name,$this->current_schema,$this->event,(integer)$options['generate'],array('locale' => $options['locale']));
        
        # add table to schema
        
        $this->head->addChild($table);
        
        #assign table as head
        $this->head = $table;
        $this->current_table = $this->head;
    
        return $this;
    }

    //  -------------------------------------------------------------------------
    
    public function addColumn($name,$options)
    {
        # merge options with default
        $options = array_merge(array(
                    'locale' => null
                    ),$options);
        
        
        # schema and table exist
        
        if(!$this->head instanceof Table) {
           throw new FakerException('Can not add new column without first setting a table and schema'); 
        }
    
        if(empty($name)) {
            throw new FakerException('Column must have a name');
        }
        
        if(isset($options['type']) === false) {
            throw new FakerException('Column requires a doctrine type');
        }
        
         # find the doctine column type
        $doctrine = $this->column_factory->create($options['type']);
        
        # merge options with defaults        
        $options = array_merge(array(
                    'locale' => $this->head->getOption('locale')
                    ),$options);
    
        # create new column
        $current_column = new Column($name,$this->head,$this->event,$doctrine,array('locale' => $options['locale']));
        
        # add the column to the table
        $this->head->addChild($current_column);
        
        $this->head = $current_column;
        $this->current_column = $this->head;
        
        return $this;
    }

    //  -------------------------------------------------------------------------
    
    public function addSelector($name,$options)
    {
        # check if schem,table,column exist
       
        if(!($this->head instanceof Column OR $this->head instanceof SelectorInterface)) {
           throw new FakerException('Can not add new Selector without first setting a table, schema and column'); 
        }
    
        # validate name for empty string
        
        if(empty($name)) {
            throw new FakerException('Selector must have a name');
        }
    

        switch($name) {
            case 'alternate':
                if(isset($options['step']) === false) {
                    throw new FakerException('Alternate type needs step');
                }
                
                $current_selector = new Alternate(
                                $name,
                                $this->head,
                                $this->event,
                                (int)$options['step']
                );
                
                $this->head->addChild($current_selector);
                
                $this->head = $current_selector;
          
            break;
        
            case 'pick' :
                if(isset($options['probability']) === false) {
                    throw new FakerException('Pick type needs a probability');
                } 
                
                $current_selector = new Pick($name,$this->head,$this->event,$options['probability']);
                
                $this->head->addChild($current_selector);
                
                $this->head = $current_selector;

            break;    
            
            case 'random' :
                $current_selector = new Random(
                                    $name,
                                    $this->head,
                                    $this->event
                );
                
                $this->head->addChild($current_selector);
                
                $this->head = $current_selector;

            break;
        
            case 'swap' :
                $current_selector = new Swap(
                                    $name,
                                    $this->head,
                                    $this->event
                );

                $this->head->addChild($current_selector);
                
                $this->head = $current_selector;

            break;
        
            case 'when' :
                
                if(!$this->head instanceof Swap) {
                    throw new FakerException('When type must have a swap parent');
                }
                
                if(isset($options['switch']) === false) {
                    throw new FakerException('When type must have a switch value');
                }
                
                $when =  new When(
                                    $name,
                                    $this->head,
                                    $this->event,
                                    $options['switch']
                );
                
                $this->head->addChild($when);

                $this->head = $when;
                
                
            break;
            
            default : throw new FakerException('Unknown Selector '.$name);    
        }
        
       
        return $this;    
    }

    //  -------------------------------------------------------------------------
    
    public function addType($name,$options)
    {
        
        # check if schema, table , column exist
       
        if(!($this->head instanceof Column OR $this->head instanceof SelectorInterface)) {
           throw new FakerException('Can not add new Selector without first setting a table and schema or column'); 
        }
    
        # validate name for empty string
        
        if(empty($name)) {
            throw new FakerException('Selector must have a name');
        }
    
        # instance the type config
    
        $current_type = $this->type_factory->create($name,$this->head);    
        
        $this->head->addChild($current_type);
        
        $this->head = $current_type;
        
        # set the default locale
        $this->setTypeOption('locale',$this->current_column->getOption('locale'));
    
        return $this;
    }

    //  -------------------------------------------------------------------------
     
    public function setTypeOption($key,$value)
    {
        #schema,table,column and type exist  
        
        if(!$this->head instanceof TypeInterface) {
            throw new FakerException('Type has not been set, can not accept option '. $key);
        }
        
        $this->head->setOption($key,$value);
        
        return $this;
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Return a completed 'Composite of Types'
      *  
      */ 
    public function build()
    {
        if($this->current_schema === null) {
            throw new FakerException('Can not build no schema set');
        }
        
        # add the writers to the composite
        
        $this->current_schema->setWriters($this->formatters);
        
        # validate the composite

        $this->current_schema->validate();
        
        $schema = $this->current_schema;
        
        # reset the builder
        
        $this->clear();
        
        return $schema;
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Clear the builder of state
      *
      *  @access public
      *  @return $this
      */    
    public function clear()
    {
        $this->head           = null;
        $this->current_schema = null;
        $this->formatters     = null;
        $this->current_column = null;
        $this->current_table  = null;
        
        return $this;
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Set the head to the parent
      *
      *  @return $this;
      *  @access public
      */
    public function end()
    {
        $this->head = $this->head->getParent();
        
        return $this;
    }
    
    //------------------------------------------------------------------
    
}
/* End of File */
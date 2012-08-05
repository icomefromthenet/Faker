<?php
namespace Faker\Components\Faker;

use Faker\Components\Faker\Composite\Column,
    Faker\Components\Faker\Composite\ForeignKey,
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
    Faker\Components\Faker\Compiler\CompilerInterface,
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

    /**
      *  @var Faker\Components\Compiler\PassCompilerPassInterface[] 
      */
    protected $compiler_passes;
    
    /**
      *  @var Faker\Components\Compiler\CompilerInterface 
      */
    protected $compiler;
    
    //  -------------------------------------------------------------------------

    
    public function __construct(EventDispatcherInterface $event,
                                PlatformFactory $platform,
                                ColumnTypeFactory $column,
                                TypeFactory $type,
                                FormatterFactory $formatter,
                                CompilerInterface $compiler,
                                array $compiler_passes)
    {
        $this->event = $event;
        $this->platform_factory = $platform;
        $this->column_factory  = $column;
        $this->formatter_factory = $formatter;
        $this->type_factory = $type;
        $this->compiler = $compiler;
        $this->compiler_passes = $compiler_passes;
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function addWriter($platform,$formatter,$options = array())
    {
        # instance a platform
        
        $platform_instance = $this->platform_factory->create($platform);
        
        $this->formatters[] = $this->formatter_factory
                                   ->create($formatter,$platform_instance,$options); 
        
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
        
        if(isset($options['name']) === false) {
            $options['name'] = $name;
        }
       
        # validate the name for empty string
        
        if(empty($name)) {
            throw new FakerException('Schema must have a name');
        }
       
        # create the new schema
        
        $this->current_schema = new Schema($name,null,$this->event,$options);
        
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
        
        if(isset($options['name']) === false) {
            $options['name'] = $name;
        }
    
        # merge options with default
        $options = array_merge(array(
                    'locale' => $this->head->getOption('locale')
                    ),$options);
        
        
        # create the new table
        $id = spl_object_hash($this->head).'.'.$name;
        $table = new Table($id,$this->current_schema,$this->event,(integer)$options['generate'],$options);
        
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
        
        # schema and table exist
        
        if(!$this->head instanceof Table) {
           throw new FakerException('Can not add new column without first setting a table and schema'); 
        }
    
        if(isset($options['type']) === false) {
            throw new FakerException('Column requires a doctrine type');
        }
        
        if(isset($options['name']) === false) {
            $options['name'] = $name;
        }
        
         # find the doctine column type
        $doctrine = $this->column_factory->create($options['type']);
        
        # merge options with defaults        
        $options = array_merge(array(
                    'locale' => $this->head->getOption('locale')
                    ),$options);
    
        # create new column
        $id = spl_object_hash($this->head).'.'.$name;
        $current_column = new Column($id,$this->head,$this->event,$doctrine,$options);
        
        # add the column to the table
        $this->head->addChild($current_column);
        
        $this->head = $current_column;
        $this->current_column = $this->head;
        
        return $this;
    }

    //------------------------------------------------------------------

    public function addForeignKey($name,$options)
    {
        # merge options with default
        $options = array_merge(array(
                    'foreignColumn' => null,
                    'foreignTable' => null,
                    ),$options);
        
        
        # schema and table exist
        
        if(!$this->head instanceof Column) {
           throw new FakerException('Can not add a Foreign-Key without first setting a column'); 
        }
    
        if(empty($name)) {
            throw new FakerException('Foreign-key must have a name unique name try foreignTable.foriegnColumn');
        }
        
        if(isset($options['name']) === false) {
            $options['name'] = $name;
        }
        
        $id = spl_object_hash($this->head);
        
        # create new column
        $foreign_key = new ForeignKey($id.'.'.$name,$this->head,$this->event,$options);
        
        # add the column to the table
        $this->head->addChild($foreign_key);
        $this->head = $foreign_key;
        
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
        
        if(isset($options['name']) === false) {
            $options['name'] = $name;
        }
    
        $parent_id = spl_object_hash($this->head);
    
        switch($name) {
            case 'alternate':
                if(isset($options['step']) === false) {
                    throw new FakerException('Alternate type needs step');
                }
                
                $current_selector = new Alternate(
                                $parent_id.'.'.$name,
                                $this->head,
                                $this->event,
                                (int)$options['step']
                );
                
                $current_selector->setOption('name',$options['name']);
                
                $this->head->addChild($current_selector);
                
                $this->head = $current_selector;
          
            break;
        
            case 'pick' :
                if(isset($options['probability']) === false) {
                    throw new FakerException('Pick type needs a probability');
                } 
                
                $current_selector = new Pick($parent_id.'.'.$name,
                                                $this->head,
                                                $this->event,
                                                $options['probability']
                                             );
                
                $current_selector->setOption('name',$options['name']);
                
                $this->head->addChild($current_selector);
                
                $this->head = $current_selector;

            break;    
            
            case 'random' :
                $current_selector = new Random(
                                    $parent_id.'.'.$name,
                                    $this->head,
                                    $this->event
                );
                
                $current_selector->setOption('name',$options['name']);
                
                $this->head->addChild($current_selector);
                
                $this->head = $current_selector;

            break;
        
            case 'swap' :
                $current_selector = new Swap(
                                    $parent_id.'.'.$name,
                                    $this->head,
                                    $this->event
                );
                
                $current_selector->setOption('name',$options['name']);

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
                                    $parent_id.'.'.$name,
                                    $this->head,
                                    $this->event,
                                    $options['switch']
                );
                
                $when->setOption('name',$options['name']);
                
                $this->head->addChild($when);

                $this->head = $when;
                
                
            break;
            
            default : throw new FakerException('Unknown Selector '.$name);    
        }
        
       
        return $this;    
    }

    //  -------------------------------------------------------------------------
    
    public function addType($name,$options = array())
    {
        
        # check if schema, table , column exist
       
        if(!($this->head instanceof Column OR $this->head instanceof SelectorInterface)) {
           throw new FakerException('Can not add new Selector without first setting a table and schema or column'); 
        }
    
        # validate name for empty string
        
        if(empty($name)) {
            throw new FakerException('Selector must have a name');
        }
        
        if(isset($options['name']) === false) {
            $options['name'] = $name;
        }
    
        # instance the type config
    
        $current_type = $this->type_factory->create($name,$this->head);    

        # set custom options        
        foreach($options as $optname => $optvalue) {
            $current_type->setOption($optname,$optvalue);
        }
        
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
    
    //------------------------------------------------------------------
    # Merge
    
    /**
      *  Bind config to the composite
      *
      *  @access public
      *  @return Builder
      */
    public function merge()
    {
        $this->current_schema->merge();
        
        foreach($this->formatters as $formatter) {
            $formatter->merge();
        }
        
        return $this;
    }
    
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Build the compiler
      *
      *  @access public
      *  @return Builder
      */
    public function compile()
    {
        $this->event->dispatch(BuildEvents::onCompileStart,new BuildEvent($this,'Starting Compile'));
        
        # run the compiler
        $compiler = $this->compiler;
        
        foreach($this->compiler_passes as $pass) {
            $compiler->addPass($pass);
        }
        
        $compiler->compile($this->current_schema);
        
        $this->event->dispatch(BuildEvents::onCompileEnd,new BuildEvent($this,'Finish Compile'));
        
        return $this;
    }
    
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Run validation on the composite
      *
      *  @return Builder
      *  @access public
      */
    public function validate()
    {
        
        $this->event->dispatch(BuildEvents::onValidationStart,new BuildEvent($this,'Starting Validation'));
        
        $this->current_schema->validate();
        
        $this->event->dispatch(BuildEvents::onValidationEnd,new BuildEvent($this,'Finished Validation'));
        
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
        
        $this->event->dispatch(BuildEvents::onBuildingStart,new BuildEvent($this,'Starting Build'));
        
        # add the writers to the composite
        $this->current_schema->setWriters($this->formatters);
        
        # merge config with there nodes in the composite
        $this->merge();
        
        # compile the composite (inject cache and check foreign keys)
        $this->compile();
        
        # validate the composite
        $this->validate();
        
        $schema = $this->current_schema;
        
         $this->event->dispatch(BuildEvents::onBuildingEnd,new BuildEvent($this,'Finished Build'));
        
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
        if(!$this->head instanceof Schema) {
            $this->head = $this->head->getParent();
        }
        
        return $this;
    }
    
    //------------------------------------------------------------------
    
    public function getSchema()
    {
        return $this->current_schema;
    }
    
    //------------------------------------------------------------------
}
/* End of File */
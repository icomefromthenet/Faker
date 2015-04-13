<?php
namespace Faker\Components\Engine\XML\Builder;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Connection;
use PHPStats\Generator\GeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Locale\LocaleInterface;
use Faker\PlatformFactory;
use Faker\Components\Templating\Loader as TemplateLoader;
use Faker\Components\Engine\Common\Formatter\FormatterFactory;
use Faker\Components\Engine\Common\BuildEvents;
use Faker\Components\Engine\Common\BuildEvent;
use Faker\Components\Engine\Common\OptionInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Compiler\CompilerInterface;
use Faker\Components\Engine\Common\Builder\NodeInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\XML\Composite\SchemaNode;
use Faker\Components\Engine\XML\Composite\TableNode;
use Faker\Components\Engine\XML\Composite\ColumnNode;
use Faker\Components\Engine\XML\Composite\ForeignKeyNode;
use Faker\Components\Engine\XML\Composite\TypeNode;
use Faker\Components\Engine\XML\Composite\WhenNode;
use Faker\Components\Engine\XML\Composite\SelectorNode;
use Faker\Components\Engine\XML\Composite\FormatterNode;
use Faker\Components\Engine\XML\Builder\SelectorAlternateBuilder;
use Faker\Components\Engine\XML\Builder\SelectorRandomBuilder;
use Faker\Components\Engine\XML\Builder\SelectorSwapBuilder;
use Faker\Components\Engine\XML\Builder\SelectorWeightBuilder;
use Faker\Components\Engine\Common\Selector\SwapSelector;
use Faker\Components\Engine\Common\Selector\RandomSelector;
use Faker\Components\Engine\Common\Selector\AlternateSelector;
use Faker\Components\Engine\Common\PositionManager;
use Faker\Components\Engine\Common\Builder\AbstractDefinition;

use Faker\Components\Engine\Common\TypeRepository;

/**
  *  Build a nodeCollection
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class NodeBuilder implements NodeInterface
{
    /*
     * @var Faker\Components\Engine\Common\Composite\CompositeInterface
     */
    protected $head;    
    
    /*
     * @var Faker\Components\Engine\XML\Composite\SchemaNode
     */
    protected $schema;    
    
    /*
     * @var Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;
    
    /*
     * @var Faker\Components\Engine\Common\TypeRepository
     */
    protected $typeFactory;
    
    /*
     * @var Doctrine\DBAL\Connection
     */
    protected $db;
    
    /*
     * @var Faker\Components\Engine\Common\Utilities
     */
    protected $util;
    
    /*
     * @var Faker\Components\Engine\Common\Compiler\CompilerInterface
     */
    protected $compiler;
    
    /*
     *  @var Faker\PlatformFactory
    */
    protected $platformFactory;
    
    /**
     * @var Faker\Components\Templating\Loader
    */
    protected $templateLoader;
    
    /*
     * @var PHPStats\Generator\GeneratorInterface
     */
    protected $defaultGenerator;
    
    /*
     * @var Faker\Locale\LocaleInterface
     */
    protected $defaultLocale;
    
    //  -------------------------------------------------------------------------
    
    
    public function __construct(EventDispatcherInterface $event,
                                TypeRepository $typeRepo,
                                Connection $connection,
                                Utilities $util,
                                CompilerInterface $compiler,
                                PlatformFactory $platformFactory,
                                FormatterFactory $formatterFactory,
                                TemplateLoader $templateLoader,
                                GeneratorInterface $defaultGenerator,
                                LocaleInterface $defaultLocale
                                
                                )
    {
        $this->eventDispatcher  = $event;
        $this->typeFactory      = $typeRepo;
        $this->db               = $connection;
        $this->util             = $util;
        $this->compiler         = $compiler;
        $this->platformFactory  = $platformFactory;
        $this->formatterFactory = $formatterFactory;
        $this->templateLoader   = $templateLoader;
        $this->defaultGenerator = $defaultGenerator;
        $this->defaultLocale    = $defaultLocale;
    }
    
    
     //  -------------------------------------------------------------------------
    
    
    public function addWriter($platform,$formatter,$options = array())
    {
        $schema = $this->getSchema();
        
        if($schema === null) {
            throw new EngineException('Must have set a schema before adding writter');
        }
        
        # instance a platform
        
        $platformInstance = $this->platformFactory->create($platform);
        
        $formatterType = $this->formatterFactory->create($formatter,$platformInstance,$options); 
        
        $formatterNode = new FormatterNode($platform.'.'.$formatter,$this->eventDispatcher,$formatterType);
        
        $this->schema->addChild($formatterNode); 
        
        return $this;
    }
    
    
    //  -------------------------------------------------------------------------
        
    public function addSchema($name, array $options = array())
    {
        # merge options with default
        $options = array_merge(array(
                    'locale' => 'en'
                    ),$options);
        
        # check if schema already set as we can have only one
        
        if($this->schema !== null) {
            throw new EngineException('Scheam already added only have one');
        }
        
        if(isset($options['name']) === false) {
            $options['name'] = $name;
        }
       
        # validate the name for empty string
        
        if(empty($name)) {
            throw new EngineException('Schema must have a name');
        }
       
        # create the new schema
        
        $this->schema = new SchemaNode($name,$this->eventDispatcher);
        
        # set the options
        foreach($options as $optionKey => $optionValue) {
            $this->schema->setOption($optionKey,$optionValue);
        }
        
        # assign schema as our head
        $this->head = $this->schema;
        
        return $this;
    }

    //  -------------------------------------------------------------------------

    public function addTable($name, array $options = array())
    {
        
        # check if schema exist
        
        if(!$this->head instanceof SchemaNode) {
            throw new EngineException('Must add a scheam first before adding a table');
        }
        
        if(isset($options['name']) === false) {
            $options['name'] = $name;
        }
    
        # merge options with default
        $options = array_merge(array(
                    'locale' => $this->head->getOption('locale')
                    ),$options);
        
        
        # create the new table
        $id = $name;
        $table = new TableNode($id,$this->eventDispatcher);
        
        
        # set the options
        foreach($options as $optionKey => $optionValue) {
            $table->setOption($optionKey,$optionValue);
        }
        
        # add table to schema
        
        $this->head->addChild($table);
        
        # assign table as head
        $this->head = $table;
    
        return $this;
    }
    
    //  -------------------------------------------------------------------------
    
    public function addColumn($name, array $options = array())
    {
        # schema and table exist
        if(!$this->head instanceof TableNode) {
           throw new EngineException('Can not add new column without first setting a table and schema'); 
        }
    
        if(isset($options['type']) === false) {
            throw new EngineException('Column requires a doctrine type');
        } else {
            
            if(Type::hasType($options['type']) === false) {
                throw new EngineException('The doctrine DBAL Type::'.$dbalTypeName.' does not exist');
            }    
        }
        
        if(isset($options['name']) === false) {
            $options['name'] = $name;
        }
        
        # merge options with defaults        
        $options = array_merge(array(
                    'locale' => $this->head->getOption('locale')
                    ),$options);
    
        # create new column
        $id = $name;
        $column = new ColumnNode($id,$this->eventDispatcher);
        
        # bind the doctine column type
        $column->setDBALType(Type::getType($options['type']));
        
        # set the options
        foreach($options as $optionKey => $optionValue) {
            $column->setOption($optionKey,$optionValue);
        }
        
        # add the column to the table
        $this->head->addChild($column);
        
        $this->head = $column;
        
        return $this;
    }
    
    //  -------------------------------------------------------------------------
    
    public function addForeignKey($name, array $options = array())
    {
        # merge options with default
        $options = array_merge(array(
                    'foreignColumn' => null,
                    'foreignTable' => null,
                    ),$options);
        
        
        # schema and table exist
        
        if(!$this->head instanceof ColumnNode) {
           throw new EngineException('Can not add a Foreign-Key without first setting a column'); 
        }
    
        if(empty($name)) {
            throw new EngineException('Foreign-key must have a name unique name try foreignTable.foriegnColumn');
        }
        
        if(isset($options['name']) === false) {
            $options['name'] = $name;
        }
        
        # create new column
        $foreignKey = new ForeignKeyNode($name,$this->eventDispatcher);
        
        # set the options
        foreach($options as $optionKey => $optionValue) {
            $foreignKey->setOption($optionKey,$optionValue);
        }
        
        # add the column to the table
        $this->head->addChild($foreignKey);
        $this->head = $foreignKey;
        
        return $this;
    }    
    
    //  -------------------------------------------------------------------------
    
    public function addType($name,$options = array())
    {
        # check if schema, table , column exist
       
        if(!($this->head instanceof ColumnNode OR
            $this->head instanceof SelectorNode OR
            $this->head instanceof WhenNode)) {
           throw new EngineException('Can not add new Selector without first setting a table and schema or column'); 
        }
    
        $locale = $this->getSchema()->getOption('locale');
    
        if($this->head->hasOption('locale')) {
            $locale =  $this->head->getOption('locale');    
        } 
    
    
        $options = array_merge(array(
                    'locale' => $locale
                    ),$options);
    
        # validate name for empty string
        
        if(empty($name)) {
            throw new EngineException('Selector must have a name');
        }
        
        if(isset($options['name']) === false) {
            unset($options['name']);
        }
    
        # instance the type builder
        if($builderName = $this->typeFactory->find($name)) {
            
            $builder = new $builderName();
            
            # instanced a builder or the type directly
            if($builder instanceof AbstractDefinition ) {
                $builder->eventDispatcher($this->eventDispatcher);
                $builder->database($this->db);
                $builder->utilities($this->util);
                $builder->templateLoader($this->templateLoader);
                $builder->locale($this->defaultLocale);
                $builder->generator($this->defaultGenerator);
                
                # build the node and add and add to head
                $type = $builder->getNode()->getType();
               
            }
            else {
                $builder->setUtilities($this->util);
                $builder->setGenerator($this->defaultGenerator);
                $builder->setLocale($this->defaultLocale);
                $builder->setEventDispatcher($this->eventDispatcher);
                $type = $builder;
            }
            
            $typeNode = new TypeNode($name,$this->eventDispatcher,$type);    
            
            
            # set custom options again they will overrite but thats ok
            # special options like name, generatorSeed etc
            foreach($options as $optname => $optvalue) {
                $typeNode->setOption($optname,$optvalue);
            }
            
            $this->head->addChild($typeNode);
            $this->head = $typeNode;
        }
        else {
            throw new EngineException("Type not exist at $name");
        }
        
        return $this;
    }
    
    
    
     //  -------------------------------------------------------------------------
     
    public function setTypeOption($key,$value)
    {
        #schema,table,column and type exist  
        
        if(!$this->head instanceof OptionInterface) {
            throw new EngineException('Type has not been set, can not accept option '. $key);
        }
        
        $this->head->setOption($key,$value);
        
        return $this;
    }
    
    //  -------------------------------------------------------------------------
    
    public function addSelector($name, array $options = array())
    {
        # check if head is a column or a selector
        if(!($this->head instanceof ColumnNode OR $this->head instanceof SelectorNode)) {
           throw new EngineException('Can not add new Selector without first setting a table, schema and column'); 
        }
    
        # validate name for empty string
        if(empty($name)) {
            throw new EngineException('Selector must have a name');
        }
        
        if(isset($options['name']) === false) {
            unset($options['name']);
        }
    
        $parent_id = $this->head->getId();
        
        switch($name) {
            case 'alternate': $currentSelectorBuilder = new SelectorAlternateBuilder($parent_id . '.alternate',
                                                                           $this->eventDispatcher,
                                                                           $this->typeFactory,
                                                                           $this->util,
                                                                           $this->defaultGenerator,
                                                                           $this->defaultLocale,
                                                                           $this->db,
                                                                           $this->templateLoader
                                                                        );
                foreach($options as $optionKey => $optionValue) {
                    $currentSelectorBuilder->attribute($optionKey,$optionValue);    
                }
                                                                        
                $currentSelector = $currentSelectorBuilder->getNode();
                
            break;
            case 'pick' :     $currentSelectorBuilder = new SelectorWeightBuilder($parent_id . '.pick',
                                                                           $this->eventDispatcher,
                                                                           $this->typeFactory,
                                                                           $this->util,
                                                                           $this->defaultGenerator,
                                                                           $this->defaultLocale,
                                                                           $this->db,
                                                                           $this->templateLoader
                                                                        );
                foreach($options as $optionKey => $optionValue) {
                    $currentSelectorBuilder->attribute($optionKey,$optionValue);    
                }
                                                                        
                $currentSelector = $currentSelectorBuilder->getNode();
                
            break;    
            case 'random' :   $currentSelectorBuilder = new SelectorRandomBuilder($parent_id . '.random',
                                                                           $this->eventDispatcher,
                                                                           $this->typeFactory,
                                                                           $this->util,
                                                                           $this->defaultGenerator,
                                                                           $this->defaultLocale,
                                                                           $this->db,
                                                                           $this->templateLoader
                                                                        );
                foreach($options as $optionKey => $optionValue) {
                    $currentSelectorBuilder->attribute($optionKey,$optionValue);    
                }
                
                $currentSelector = $currentSelectorBuilder->getNode();
                
            break;
            case 'swap' :     $currentSelectorBuilder = new SelectorSwapBuilder($parent_id . '.swap',
                                                                           $this->eventDispatcher,
                                                                           $this->typeFactory,
                                                                           $this->util,
                                                                           $this->defaultGenerator,
                                                                           $this->defaultLocale,
                                                                           $this->db,
                                                                           $this->templateLoader
                                                        );
                
                foreach($options as $optionKey => $optionValue) {
                    $currentSelectorBuilder->attribute($optionKey,$optionValue);    
                }
                                                                        
                $currentSelector = $currentSelectorBuilder->getNode();
                
            break;
            case 'when' :
                
                if(!$this->head instanceof SelectorNode) {
                    throw new EngineException('When node must have a selector as a parent');
                }
                
                if(!$this->head->getInternal() instanceof SwapSelector) {
                    throw new EngineException('When node must have a swap node as parent');
                }
                
                $currentSelector = new WhenNode($parent_id . '.when',$this->eventDispatcher);
                
                foreach($options as $optionKey => $optionValue) {
                    $currentSelector->setOption($optionKey,$optionValue);    
                }
                
                
            break;
            
            default : throw new EngineException('Unknown Selector:: '.$name);    
        }
    
        
        # add selector to head
        $this->head->addChild($currentSelector);
        
        # set the current selector to new head
        $this->head = $currentSelector;
           
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
        
        $this->eventDispatcher->dispatch(BuildEvents::onValidationStart,new BuildEvent($this,'Starting Validation'));
        
        $this->schema->validate();
        
        $this->eventDispatcher->dispatch(BuildEvents::onValidationEnd,new BuildEvent($this,'Finished Validation'));
        
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
        $this->eventDispatcher->dispatch(BuildEvents::onCompileStart,new BuildEvent($this,'Starting Compile'));
        
        # run the compiler
        $this->compiler->compile($this->schema);
        
        $this->eventDispatcher->dispatch(BuildEvents::onCompileEnd,new BuildEvent($this,'Finish Compile'));
        
        return $this;
    }
    
     //  -------------------------------------------------------------------------
    
    /**
      *  Return a completed 'Composite of Types'
      *  
      */ 
    public function build()
    {
        if($this->schema === null) {
            throw new EngineException('Can not build no schema set');
        }
        
        $this->eventDispatcher->dispatch(BuildEvents::onBuildingStart,new BuildEvent($this,'Starting Build'));
        
        # validate the composite
        $this->validate();

                
        # compile the composite (inject cache and check foreign keys)
        $this->compile();
        
        $this->eventDispatcher->dispatch(BuildEvents::onBuildingEnd,new BuildEvent($this,'Finished Build'));
        
        return $this->schema;
    }
    
    //  -------------------------------------------------------------------------
    # Node Interface
    /**
      *  Set the head to the parent
      *
      *  @return NodeBuilder;
      *  @access public
      */
    public function end()
    {
        # register swap managers if we have a swap selector node        
        if($this->head instanceof SelectorNode) {
            $internal = $this->head->getInternal();
            
            if($internal instanceof SwapSelector) {
                foreach($this->head->getChildren() as $selectorNode) {
                    if($selectorNode instanceof WhenNode) {
                        $internal->registerSwap(new PositionManager($selectorNode->getOption('until')));    
                    }
                }
            }
            
            if($internal instanceof RandomSelector){
                $setSize = count($this->head->getChildren());
                $internal->setOption('set',$setSize);
            }
            
            if($internal instanceof AlternateSelector) {
                $setSize = count($this->head->getChildren());
                $internal->setOption('set',$setSize);
            }
        }
        
        
        if(!$this->head instanceof SchemaNode) {
            $this->head = $this->head->getParent();
        }
        
        return $this;
    }
    
    public function getNode()
    {
        
    }
    
    
    public function setParent(NodeInterface $parent)
    {
        
    }
    
    public function getParent()
    {
        return null;
    }
    
    //-------------------------------------------------------
    # Properties
    
    /**
     *  Return the root (SchemaNode)
     *
     *  @access public
     *  @return Faker\Components\Engine\XML\Composite\SchemaNode
     *
    */
    public function getSchema()
    {
        return $this->schema;
    }
    
    /**
     *  Return the head
     *
     *  @access public
     *  @return Faker\Components\Engine\Common\Composite\CompositeInterface
     *
    */
    public function getHead()
    {
        return $this->head;
    }
    
}
/* End of File */


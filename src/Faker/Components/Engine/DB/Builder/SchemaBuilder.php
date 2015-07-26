<?php
namespace Faker\Components\Engine\DB\Builder;

use Closure;
use PHPStats\Generator\GeneratorInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


use Faker\Project;
use Faker\Locale\LocaleInterface;
use Faker\PlatformFactory;
use Faker\Components\Templating\Loader;
use Faker\Components\Engine\Common\BuildEvents;
use Faker\Components\Engine\Common\BuildEvent;
use Faker\Components\Engine\Common\Builder\ParentNodeInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\TypeRepository;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Builder\NodeInterface;
use Faker\Components\Engine\Common\Formatter\FormatterBuilder;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Formatter\FormatterFactory;
use Faker\Components\Engine\Common\Compiler\CompilerInterface;
use Faker\Components\Engine\Common\Datasource\DatasourceRepository;
use Faker\Components\Engine\DB\Composite\SchemaNode;


/**
  *  Build a SchemaNode 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class SchemaBuilder implements ParentNodeInterface
{
    protected $rootNode;
    protected $children;
    
    protected $locale;
    protected $connection;
    protected $utilities;
    protected $generator;
    protected $typeRepo;
    protected $templateLoader;
    protected $channelName;
    protected $oTableCollection;
    
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */
    protected $event;
    
    /**
      *  @var string the database name 
      */
    protected $name;
    
    /**
      *   @var Faker\Components\Engine\Common\Formatter\FormatterFactory
      */
    protected $formatterFactory;
    
    /**
      *  @var Faker\PlatformFactory 
      */
    protected $platformFactory;
    
    /**
     *  @var CompilerInterface
    */
    protected $compiler;
    
    /**
     *  @var DirectedGraphVisitor
    */
    protected $visitor;
    
    /**
     * @var Faker\Components\Engine\Common\Datasource\DatasourceRepository
     */ 
    protected $datasourceRepo;
    
    /**
      *  Class Constructor 
      */
    public function __construct($name,
                                EventDispatcherInterface $event,
                                TypeRepository $repo,
                                LocaleInterface $locale,
                                Utilities $util,
                                GeneratorInterface $gen,
                                Connection $conn,
                                Loader $loader,
                                PlatformFactory $platformFactory,
                                FormatterFactory $formatterFactory,
                                CompilerInterface $compiler,
                                DatasourceRepository $datasourceRepo,
                                $channelName = null
                                )
    {
        $this->name             = $name;
        $this->event            = $event;
        $this->typeRepo         = $repo;
        $this->locale           = $locale;
        $this->utilities        = $util;
        $this->generator        = $gen;
        $this->connection       = $conn;
        $this->templateLoader   = $loader;
        $this->children         = array();
        $this->platformFactory  = $platformFactory;
        $this->formatterFactory = $formatterFactory;
        $this->compiler         = $compiler;
        $this->datasourceRepo   = $datasourceRepo;
        $this->channelName      = $channelName;
    }
    
    
     /**
      *  Define the tables that this schema has
      *
      *  @access public
      *  @return Faker\Components\Engine\DB\Builder\TableCollection
      */
    public function describe()
    {
       
       if(null === $this->oTableCollection) {
       
          $this->oTableCollection = new TableCollection($this->name,
                                               $this->event,
                                               $this->typeRepo,
                                               $this->utilities,
                                               $this->generator,
                                               $this->locale,
                                               $this->connection,
                                               $this->templateLoader
                                            );
        
          $this->oTableCollection->setParent($this);
        }
        
        return $this->oTableCollection;
    }
    
    /**
      *  Add a writter
      *
      *  @return Faker\Components\Engine\Common\Formatter\FormatterBuilder
      */
    public function addWriter()
    {
        $builder = new FormatterBuilder($this->event,$this->formatterFactory,$this->platformFactory);
        
        $builder->setParent($this);
        
        return $builder;
    }
    
    /**
     * Add a new Datasource via the definition
     * 
     * @return Faker\Components\Engine\DB\Builder\DatasourceBuilder
     */ 
    public function addDatasource()
    {
        $builder = new DatasourceBuilder('Datasources'
                                     ,$this->event
                                     ,$this->typeRepo
                                     ,$this->utilities
                                     ,$this->generator
                                     ,$this->locale
                                     ,$this->connection
                                     ,$this->templateLoader
                                     ,$this->datasourceRepo);
                        
        $builder->setParent($this);
        
        return $builder;                             
                                     
    }
    
    
    //------------------------------------------------------------------
    # Event helpers
    
    /**
     * Attach an event handler to execute before generating starts
     * 
     * @param Callable   $fn
     */ 
    public function onGenerateStart($fn)
    {
        $this->event->addListener(FormatEvents::onSchemaStart,$fn);
        
        return $this;
    }
    
    /**
     * Attach event handler to execute after the schema has stopped generating
     * 
     * @param Callable   $fn
     */ 
    public function onGenerateEnd($fn)
    {
        $this->event->addListener(FormatEvents::onSchemaEnd,$fn);
        
        return $this;
    }
    
    //------------------------------------------------------------------
    # ParentNodeInterface
    
    public function append(CompositeInterface $node)
    {
        $this->children[] = $node;
    }
    
    public function children()
    {
        return $this->children;
    }
    
    
    public function setParent(NodeInterface $parent)
    {
        return null;
    }
    
    public function getParent()
    {
        return null;
    }
    
    public function getNode()
    {
        if($this->rootNode === null) {
            $this->rootNode   = new SchemaNode($this->name,$this->event,$this->channelName);
        }
        
        return $this->rootNode;
    }
    
    /**
      *  Builds a SchemaNode
      *
      *  @access public
      *  @return Faker\Components\Engine\DB\Composite/SchemaNode
      */
    public function end()
    {
       
        $node      = $this->getNode();
        $children  = $this->children();
        $compiiler = $this->compiler;
       
        foreach($children as $child) {
            
            $node->addChild($child);
        }
        
        # ensure the correct channel is being used before start emitting events
        $this->event->switchChannel($this->channelName);
        
        
        $this->event->dispatch(BuildEvents::onBuildingStart,new BuildEvent($this,'Started build of entity '.$this->name));
    
        
        # run validation routines        
        $this->event->dispatch(BuildEvents::onValidationStart,new BuildEvent($this,'Started validation of entity '.$this->name));
        $node->validate();
        $this->event->dispatch(BuildEvents::onValidationEnd,new BuildEvent($this,'Finished validation of entity '.$this->name));
    
        # run compiler  
        $this->event->dispatch(BuildEvents::onCompileStart,new BuildEvent($this,'Started compile of entity '.$this->name));
        $this->compiler->compile($node);
        $this->event->dispatch(BuildEvents::onCompileEnd,new BuildEvent($this,'Finished compile of entity '.$this->name));    
        
        
        
        $this->event->dispatch(BuildEvents::onBuildingEnd,new BuildEvent($this,'Finished build of entity '.$this->name));
    
    
    
        return $node;
    }
    
    
}
/* End of File */
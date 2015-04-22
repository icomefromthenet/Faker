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
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\TypeRepository;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Builder\NodeInterface;
use Faker\Components\Engine\Common\Formatter\FormatterBuilder;
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
                                DatasourceRepository $datasourceRepo
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
    }
    
    
     /**
      *  Define the tables that this schema has
      *
      *  @access public
      *  @return Faker\Components\Engine\DB\Builder\TableCollection
      */
    public function describe()
    {
        $tableCollection = new TableCollection($this->name,
                                               $this->event,
                                               $this->typeRepo,
                                               $this->utilities,
                                               $this->generator,
                                               $this->locale,
                                               $this->connection,
                                               $this->templateLoader
                                            );
        
        $tableCollection->setParent($this);
        
        return $tableCollection;
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
        return new DatasourceBuilder('Datasources'
                                     ,$this->event
                                     ,$this->typeRepo
                                     ,$this->utilities
                                     ,$this->generator
                                     ,$this->locale
                                     ,$this->connection
                                     ,$this->templateLoader
                                     ,$this->datasourceRepo);
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
            $this->rootNode   = new SchemaNode($this->name,$this->event);
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
    
    //------------------------------------------------------------------
    # Static Constructor
    
    
    /**
      *  Static Constructor
      *
      *  @param Faker\Project the DI container
      *  @param string $name of the entity
      *  @param Faker\Locale\LocaleInterface $locale to use
      *  @param Faker\Components\Engine\Common\Utilities  $util
      *  @param PHPStats\Generator\GeneratorInterface $util
      */
    public static function create(Project $container,
                                  $name,
                                  LocaleInterface $locale = null,
                                  Utilities $util = null,
                                  GeneratorInterface $gen = null
                                  )
    {
        $repo               = $container->getEngineTypeRepository();
        $event              = $container->getEventDispatcher();
        $conn               = $container->getGeneratorDatabase();
        $loader             = $container->getTemplatingManager()->getLoader();
        $platformFactory    = $container->getDBALPlatformFactory();
        $formatterFactory   = $container->getFormatterFactory($event);
        $compiler           = $container->getEngineCompiler();
        $datasourceRepo     = $container->getDatasourceRepository();
        
        if($locale === null) {
            $locale = $container->getLocaleFactory()->create('en');
        }
        
        if($util === null) {
            $util = $container->getEngineUtilities();
        }
        
        if($gen === null) {
            $gen = $container->getDefaultRandom();
        }
        
    
        return new self($name,$event,$repo,$locale,$util,$gen,$conn,$loader,$platformFactory,$formatterFactory,$compiler,$datasourceRepo);
    }
    
}
/* End of File */
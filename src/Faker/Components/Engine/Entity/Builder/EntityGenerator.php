<?php
namespace Faker\Components\Engine\Entity\Builder;

use Faker\Project;
use Faker\Locale\LocaleInterface;
use Faker\Components\Templating\Loader;
use Faker\Components\Engine\Common\Builder\ParentNodeInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\TypeRepository;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Builder\NodeInterface;
use Faker\Components\Engine\Entity\Composite\EntityNode;
use Faker\Components\Engine\Entity\EntityIterator;
use Faker\Components\Engine\Common\BuildEvents;
use Faker\Components\Engine\Common\BuildEvent;

use Closure;
use PHPStats\Generator\GeneratorInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


/**
  *  Used to construct generator composite to represent entities
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class EntityGenerator implements ParentNodeInterface
{
    protected $rootNode;
    protected $children;
    protected $mapClosure;
    
    
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
      *  @var string the entity name 
      */
    protected $name;
    
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
                                Loader $loader
                                )
    {
        $this->name           = $name;
        $this->event          = $event;
        $this->typeRepo       = $repo;
        $this->locale         = $locale;
        $this->utilities      = $util;
        $this->generator      = $gen;
        $this->connection     = $conn;
        $this->templateLoader = $loader;
        $this->children       = array();
        $this->mapClosure     = null;  
    }

 
    //------------------------------------------------------------------
    # Event Helpers
    
    protected function beforeEntityGenerate(Closure $fn)
    {
        $this->event->addListener(FormatEvents::onTableStart,$fn);
        
        return $this;
    }
    
    
    protected function afterEntityGenerate(Closure $fn)
    {
        $this->event->addListener(FormatEvents::onTableEnd,$fn);
        
        return $this;
    }
    
    
    protected function beforeFieldGenerate(Closure $fn)
    {
        $this->event->addListener(FormatEvents::onColumnStart,$fn);
        
        return $this;
    }
    
    
    protected function afterFieldGenerate(Closure $fn)
    {
        $this->event->addListener(FormatEvents::onColumnEnd,$fn);
        
        return $this;
    }
    
    //------------------------------------------------------------------
    
    /**
      *  Defines the fields and types that will be generated
      *
      *  @access public
      *  @return \Faker\Components\Engine\Entity\Builder\FieldCollection a field collection
      */
    public function describe()
    {
        $fieldsColl     = new FieldCollection($this->name.'-field_collection',
                                              $this->event,
                                              $this->typeRepo,
                                              $this->utilities,
                                              $this->generator,
                                              $this->locale,
                                              $this->connection,
                                              $this->templateLoader);
        $fieldsColl->setParent($this);
        
        return $fieldsColl;
    }
    
    /**
      *  Maps a closure that will execute after each entity finished generating
      *  useful to map the GenericEntity to a Domain Entity
      *
      *  @param \Closure $callable
      *  @return EntityBuilder
      */    
    public function map(\Closure $callable)
    {
        # Store this closure to bind into resultIterator
        $this->mapClosure = $callable;
        
        return $this;
    }
    
    /**
      *  Setup the generate to make x entities
      *
      *  @access public
      *  @param integer $number the number of entities to kaje
      *  @return Faker\Components\Engine\Entity\EntityIterator
      */
    public function fake($number)
    {
       
       if(is_int($number) === false) {
            throw new EngineException('Number to generate must be an integer > 0');
       }
       
       if($number < 0) {
            throw new EngineException('Number to generate must be an integer > 0');
       }
       
       return new EntityIterator($number,$this->end(),$this->mapClosure,false);
       
    }
    
    
    //------------------------------------------------------------------
    
    /**
      *  Static Constructor
      *
      *  @param \Faker\Project the DI container
      *  @param string $name of the entity
      *  @param \Faker\Locale\LocaleInterface $locale to use
      *  @param \PHPStats\Generator\GeneratorInterface $util
      *  @return \Faker\Components\Engine\Entity\Builder\EntityGenerator
      */
    public static function create(Project $container,
                                  $name,
                                  LocaleInterface $locale = null,
                                  Utilities $util = null,
                                  GeneratorInterface $gen = null
                                 )
    {
        $repo   = $container->getEngineTypeRepository();
        $event  = $container->getEventDispatcher();
        $conn   = $container->getGeneratorDatabase();
        $loader = $container->getTemplatingManager()->getLoader();
        
        if($locale === null) {
            $locale = $container->getLocaleFactory()->create('en');
        }
        
        if($util === null) {
            $util = $container->getEngineUtilities();
        }
        
        if($gen === null) {
            $gen = $container->getDefaultRandom();
        }
    
        return new self($name,$event,$repo,$locale,$util,$gen,$conn,$loader);
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
            $this->rootNode  = new EntityNode($this->name,$this->event); 
        }
        
        return $this->rootNode;
    }
    
    public function end()
    {
        $this->event->dispatch(BuildEvents::onBuildingStart,new BuildEvent($this,'Started to build entity' .$this->name));
       
        # attach child nodes to root
        $node     = $this->getNode(); 
        $children = $this->children();
        
        # append the fieldNodes to the rootEntity
        foreach($children as $child) {
            $node->addChild($child);
        }
        
        # run validation routines        
        $this->event->dispatch(BuildEvents::onValidationStart,new BuildEvent($this,'Started validation of entity '.$this->name));
        $node->validate();
        $this->event->dispatch(BuildEvents::onValidationEnd,new BuildEvent($this,'Finished validation of entity '.$this->name));
        
        # run compiler  
        $this->event->dispatch(BuildEvents::onCompileStart,new BuildEvent($this,'Started compile of entity '.$this->name));
        $this->event->dispatch(BuildEvents::onCompileEnd,new BuildEvent($this,'Finished compile of entity '.$this->name));    
        
        
        $this->event->dispatch(BuildEvents::onBuildingEnd,new BuildEvent($this,'Finished build of entity '.$this->name));
    
            
        return $node;
    }
    
}
/* End of File */
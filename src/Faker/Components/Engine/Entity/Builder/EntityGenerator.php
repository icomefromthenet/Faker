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
      *  @param \Faker\Components\Engine\Common\TypeRepository $repo;
      *  @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event
      *  @param \Faker\Locale\LocaleInterface $locale to use
      *  @param \PHPStats\Generator\GeneratorInterface $util
      *  @param \Doctrine\DBAL\Connection $conn
      *  @param \Faker\Components\Templating\Loader $loader
      *  @return \Faker\Components\Engine\Entity\Builder\EntityGenerator
      */
    public static function create(Project $container,
                                  $name,
                                  EventDispatcherInterface $event = null,
                                  TypeRepository $repo = null,
                                  LocaleInterface $locale = null,
                                  Utilities $util = null,
                                  GeneratorInterface $gen = null,
                                  Connection $conn = null,
                                  Loader $loader = null)
    {
        if($repo === null) {
            $repo = $container->getEngineTypeRepository();
        }
        
        if($event === null) {
            $event = $container->getEventDispatcher();
        }
        
        if($locale === null) {
            $locale = $container->getLocaleFactory()->create('en');
        }
        
        if($util === null) {
            $util = $container->getEngineUtilities();
        }
        
        if($gen === null) {
            $gen = $container->getDefaultRandom();
        }
        
        if($conn === null) {
            $conn = $container->getGeneratorDatabase();
        }
        
        if($loader === null) {
            $loader = $container->getTemplatingManager()->getLoader();
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
    
    public function getNode()
    {
        return new EntityNode($this->name,$this->event);
    }
    
    
    public function setParent(NodeInterface $parent)
    {
        return null;
    }
    
    public function getParent()
    {
        return null;
    }
    
    public function end()
    {
        if($this->rootNode === null) {
            $this->rootNode   = $this->getNode();    
        }
        
        $children = $this->children();
        
        # append the fieldNodes to the rootEntity
        foreach($children as $child) {
            $this->rootNode->addChild($child);
        }
        
        # run validation routines
        $this->rootNode->validate();
        
        return $this->rootNode;
    }
    
}
/* End of File */
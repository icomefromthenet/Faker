<?php
namespace Faker\Components\Engine\Entity\Builder;

use Closure;
use PHPStats\Generator\GeneratorInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Project;
use Faker\Components\Engine\Common\Builder\ParentNodeInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\TypeRepository;
use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\Entity\Composite\EntityNode;

/**
  *  Used to construct generator composite to represent entities
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class EntityGenerator implements ParentNodeInterface
{
    protected $rootNode;
    protected $rootEntity;
    protected $mapClosure;
    
    
    protected $locale;
    protected $connection;
    protected $utilities;
    protected $generator;
    protected $typeRepo;
    
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
                                Connection $conn)
    {
        $this->name       = $name;
        $this->event      = $event;
        $this->typeRepo   = $repo;
        $this->locale     = $locale;
        $this->utilities  = $util;
        $this->generator  = $gen;
        $this->connection = $conn;
        $this->children = array();
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
      *  @return FieldBuilderCollection
      */
    public function describe()
    {
        if($this->rootNode === null) {
            
            # instance new FieldBuilder only do this once per instance.
            # chain methods are optional.
            $this->rootNode = new FieldBuilder($this->getNode(),$this->name,$event,$root,$this->typeRepo,$this->utilities,$this->generator,$this->locale,$this->connection);
            
            # setup composite relationship 
            $this->rootNode->setParent($this);
        }
        
        return $this->rootNode;
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
    
    
    public function generate($number)
    {
       
       $iterator = null;
       
       # bind the map closure to the result iterator        
       
       
       
       
       return $iterator;
       
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
      */
    public static function create(Project $container,
                                  $name,
                                  EventDispatcherInterface $event = null,
                                  TypeRepository $repo = null,
                                  LocaleInterface $locale = null,
                                  Utilities $util = null,
                                  GeneratorInterface $gen = null,
                                  Connection $conn = null )
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
            $util = $container->getGeneratorDatabase();
        }
        
    
        return new self($name,$event,$repo,$locale,$util,$gen,$conn);
    }
    
    //------------------------------------------------------------------
    # ParentNodeInterface
    
    /**
      *  Append a node to this one
      *
      *  @access public
      *  @return NodeInterface
      *  @param  Faker\Components\Engine\Common\Composite\CompositeInterface $node
      */
    public function append(CompositeInterface $node)
    {
        $this->rootEntity = $node;
    }
    
    /**
      *  Return this nodes children
      *
      *  @access public
      *  @return array[Faker\Components\Engine\Common\Composite\CompositeInterface]
      */
    public function children()
    {
        return $this->rootEntity;
    }
    
    
    /**
      *  Fetch the generator composite node managed by this builder node
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    public function getNode()
    {
        return new EntityNode($this->name,$this->event);
    }
    
    
    /**
    * Sets the parent node.
    *
    * @param ParentNodeInterface $parent The parent
    */
    public function setParent(NodeInterface $parent)
    {
        return null;
    }
    
    /**
      *  Return the assigned parent
      *
      *  @param access
      *  @return NodeInterface
      */
    public function getParent()
    {
        return null;
    }
    
    /**
    * Return the parent node and build the node
    * defined by this builder and append it to the parent.
    *
    * @return NodeInterface The builder of the parent node
    */
    public function end()
    {
        return $this;
    }
    
}
/* End of File */
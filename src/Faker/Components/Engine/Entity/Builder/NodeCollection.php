<?php
namespace Faker\Components\Engine\Entity\Builder;

use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Builder\ParentNodeInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\TypeRepository;
use Faker\Components\Templating\Loader;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PHPStats\Generator\GeneratorInterface;
use Doctrine\DBAL\Connection;

/**
  *  Abstract Basic Node Collection
  *
  *  Children must implement end() and getNode()
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
abstract class NodeCollection implements ParentNodeInterface
{
    
    protected $children;
    
    protected $parentNode;
    
    protected $name;
    protected $utilities;
    protected $locale;
    protected $generator;
    protected $eventDispatcher;
    protected $database;
    protected $repo;
    protected $templateLoader;
    
    /**
      *  Class Constructor
      *
      *  @access public
      *  @return void
      */
    public function __construct($name,
                                EventDispatcherInterface $event,
                                TypeRepository $repo,
                                Utilities $util,
                                GeneratorInterface $generator,
                                LocaleInterface $locale,
                                Connection $conn,
                                Loader $loader
                                )
    {
        $this->name            = $name;
        
        $this->eventDispatcher = $event;
        $this->repo            = $repo;
        $this->database        = $conn;
        $this->generator       = $generator;
        $this->utilities       = $util;
        $this->locale          = $locale;
        $this->templateLoader  = $loader;
    }
    
    
    /**
      *  Append a node to this one
      *
      *  @access public
      *  @return NodeInterface
      *  @param  Faker\Components\Engine\Common\Composite\CompositeInterface $node
      */
    public function append(CompositeInterface $node)
    {
        $this->children[] = $node;
    }
    
    /**
      *  Return this nodes children
      *
      *  @access public
      *  @return array[Faker\Components\Engine\Common\Composite\CompositeInterface]
      */
    public function children()
    {
        return $this->children;
    }
    
    
     /**
      *  Fetch the generator composite node managed by this builder node
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    abstract public function getNode();
    
    
    
    /**
    * Sets the parent node.
    *
    * @param ParentNodeInterface $parent The parent
    */
    public function setParent(NodeInterface $parent)
    {
        $this->parentNode = $parent;
    }
    
    /**
      *  Return the assigned parent
      *
      *  @param access
      *  @return NodeInterface
      */
    public function getParent()
    {
        return $this->parentNode;
    }
    
    /**
    * Return the parent node and build the node
    * defined by this builder and append it to the parent.
    *
    * @return \Faker\Components\Engine\Common\Builder\NodeInterface The builder of the parent node
    */
    abstract public function end();
    
}
/* End of File */
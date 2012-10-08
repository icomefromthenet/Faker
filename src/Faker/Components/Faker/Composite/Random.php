<?php
namespace Faker\Components\Faker\Composite;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Formatter\FormatEvents,
    Faker\Components\Faker\Formatter\GenerateEvent,
    PHPStats\Generator\GeneratorInterface,
    Symfony\Component\EventDispatcher\EventDispatcherInterface;


class Random extends BaseComposite implements CompositeInterface , SelectorInterface
{
    
    /**
      *  @var CompositeInterface 
      */
    protected $parent_type;
    
    /**
      *  @var CompositeInterface[] 
      */
    protected $child_types = array();
    
    /**
      *  @var string the id of the component 
      */
    protected $id;
    
    /**
      *  @var Doctrine\DBAL\Types\Type the mapper to convert php types into database representations
      */
    protected $column_type;
    
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $event;
    
    /**
      *  @var PHPStats\Generator\GeneratorInterface 
      */
    protected $generator;
    
    /**
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param CompositeInterface $parent 
      */
    public function __construct($id, CompositeInterface $parent, EventDispatcherInterface $event)
    {
        $this->id = $id;
        $this->setParent($parent);
        $this->event = $event;
        
    }
    
    /**
      *  @inheritdoc 
      */
    public function generate($rows,$values = array())
    {
        
        $return = $this->generator->generate(0,(count($this->child_types)-1));
        $index = round($return);
        
        return $this->child_types[$index]->generate($rows,$values);
    }
    
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @inheritdoc 
      */
    public function getId()
    {
        return $this->id;
    }
    
    
    /**
      * @inheritdoc
      */
    public function getParent()
    {
        return $this->parent_type;
    }

    /**
      * @inheritdoc  
      */
    public function setParent(CompositeInterface $parent)
    {
        $this->parent_type = $parent;
    }
    
    
    /**
      *  @inheritdoc
      */
    public function getChildren()
    {
        return $this->child_types;
    }
    
    
    /**
      *  @inheritdoc
      */
    public function addChild(CompositeInterface $child)
    {
        return array_push($this->child_types,$child);
    }
    
    
    /**
      *  Get Event Dispatcher
      *
      *  @return Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */ 
    public function getEventDispatcher()
    {
        return $this->event;
    }

    
    public function toXml()
    {
        return '';
    }
    
    /**
      *  Set the random number generator
      *
      *  @access public
      *  @return PHPStats\Generator\GeneratorInterface
      */
    public function setGenerator(GeneratorInterface $generator)
    {
	$this->generator = $generator;
    }
    
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
       # ask children to validate themselves
        
        foreach($this->getChildren() as $child) {
        
          $child->validate(); 
        }
        
        # check that children have been added
        
        if(count($this->getChildren()) === 0) {
          throw new FakerException('Random must have at least 2 types');
        }

        return true;       
        
    }

    //  -------------------------------------------------------------------------
}
/* End of File */
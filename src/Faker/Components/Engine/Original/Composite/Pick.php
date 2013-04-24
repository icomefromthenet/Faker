<?php
namespace Faker\Components\Engine\Original\Composite;

use Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Common\Formatter\FormatEvents,
    Faker\Components\Engine\Original\Formatter\GenerateEvent,
    PHPStats\Generator\GeneratorInterface,
    Symfony\Component\EventDispatcher\EventDispatcherInterface;


class Pick extends BaseComposite implements  CompositeInterface , SelectorInterface
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
      *  @var double the figure to use as probability cutoff 
      */
    protected $probability;
    
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
      *  @param EventDispatcherInterface $event
      *  @param double $probability
      */
    public function __construct($id, CompositeInterface $parent, EventDispatcherInterface $event,$probability)
    {
        $this->id = $id;
        $this->setParent($parent);
        $this->event = $event;
        
        if (is_numeric($probability) === FALSE) {
            throw new FakerException('Probability must be a number');
        }

        $probability = (double) $probability;

        if ($probability > 100 || $probability < 0) {
            throw new FakerException('Probability must be a between 0-1 or 0-100');
        }

        
        $this->probability = $probability;
    }
    
    
   //  -------------------------------------------------------------------------
    
    /**
      *  @inheritdoc 
      */
    public function generate($rows,&$values = array())
    {
        $index = (round($this->generator->generate(0,100)) <= $this->probability) ? 0 : 1;
        
        if(isset($this->child_types[$index]) == false) {
           throw new FakerException('Pick must have TWO types set');
        }

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
        
        if(count($this->getChildren()) <= 1) {
          throw new FakerException('Pick must have at least 2 types');
        }

        return true;  
        
    }

    //  -------------------------------------------------------------------------
}
/* End of File */
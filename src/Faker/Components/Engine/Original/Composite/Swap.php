<?php
namespace Faker\Components\Engine\Original\Composite;

use Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Common\Formatter\FormatEvents,
    Faker\Components\Engine\Original\Formatter\GenerateEvent,
    Symfony\Component\EventDispatcher\EventDispatcherInterface;


class Swap extends BaseComposite implements CompositeInterface , SelectorInterface
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
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface
      */
    protected $event;

    /**
      *  @var integer[] the ranges to swap from  
      */
    protected $switch_map = null;    
    
    /**
      *  @var integer current index 
      */
    protected $current;
    
    /**
      *  Class construtor
      *
      *  @access public
      *  @return void
      *  @param string $id the schema name
      *  @param CompositeInterface $parent
      *  @param EventDispatcherInterface $event
      */
    public function __construct($id, CompositeInterface $parent, EventDispatcherInterface $event)
    {
        $this->id = $id;
        $this->setParent($parent);
        $this->event = $event;
        
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function generate($rows,$values = array())
    {
      
       # has the switch map been populated?
       if($this->switch_map === null) {
            
            $this->switch_map = array();
            
            foreach($this->child_types as $index => $type) {
                $this->switch_map[$index] = (integer) $type->getSwap();
            } 
            
            reset($this->switch_map);
            $this->current = key($this->switch_map);
       }
       
       # if at 0 pass reset the counter and move the position to
       # the next child type in the switch map
       if($this->switch_map[$this->current] === 0) {
            $this->switch_map[$this->current] = (integer) $this->child_types[$this->current]->getSwap();
            ++$this->current;
       }
       
       # about to use a generator pass remove it from total left.
       --$this->switch_map[$this->current];
       
       # return the generator pass
       return $this->child_types[$this->current]->generate($rows,$values);
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
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
       # ask children to validate themselves
        
        foreach($this->getChildren() as $child) {
        
          $child->validate(); 
        }
        
        # check that children have been added
        
        if(count($this->getChildren()) === 0) {
          throw new FakerException('Swap must have at least 1 when');
        }

        return true;       
        
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */
<?php
namespace Faker\Components\Engine\Common\Formatter;

use Symfony\Component\EventDispatcher\Event;
use Faker\Components\Engine\Common\Composite\CompositeInterface;

/**
  *  Event is used for events found in \Faker\Components\Engine\Original\FormatEvents;
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.0
  */
class GenerateEvent extends Event
{
    /**
      *  @var mixed associate array of generated values
      */
    protected $values;
    
    /**
      *  @var string the id of the type used to generate 
      */
    protected $id;

    /**
      *  @var  Faker\Components\Engine\Original\Composite\CompositeInterface
      */
    protected $node;
    
    /**
      *  Class constructor
      *
      *  @param CompositeInterface $formatter
      *  @param mixed[] $values associate array of values generated
      *  @param string $id the component id example to schema name
      */
    public function __construct(CompositeInterface $node, array $values, $id)
    {
        $this->values = $values;
        $this->id     = $id;
        $this->node   = $node;
    }
    
    /**
      *  Fetch the generated values
      *
      *  @return mixed[]
      */
    public function getValues()
    {
        return $this->values;
    }
    
    /**
      *  Fetch the id of the generating type
      *
      *  @return string the type name
      */
    public function getId()
    {
        return $this->id;
    }
    
    /**
      *  Fetch the bound generating type
      *
      *  @return CompositeInterface
      */
    public function getNode()
    {
        return $this->node;
    }
}


/* End of File */
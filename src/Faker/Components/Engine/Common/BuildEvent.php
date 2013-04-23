<?php
namespace Faker\Components\Engine\Common;

use Symfony\Component\EventDispatcher\Event;
use Faker\Components\Engine\Common\Builder\NodeInterface;

/**
  *  Event is used for events found in \Faker\Components\Engine\Common\BuildEvents
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.3
  */
class BuildEvent extends Event
{
    /**
      *  @var  Faker\Components\Engine\Common\Builder\NodeInterface
      */
    protected $builder;
    
    /**
      *  @var string an info string 
      */
    protected $info;
    
    /**
      *  Class constructor
      *
      * @access public
      * @param NodeInterface $builder instance of builder
      * @param string $info an info string
      */
    public function __construct(NodeInterface $builder,$info)
    {
        $this->builder = $builder;
        $this->info    = $info;
    }
    
    /**
      *  Fetch the info string
      *  
      *  @return string
      *  @access public
      */
    public function getInfo()
    {
        return $this->info;
    }
    
    /**
      *  Fetch the builder instance
      *
      *  @access public
      *  @return NodeInterface
      */
    public function getBuilder()
    {
        return $this->builder;
    }
}
/*End of File */
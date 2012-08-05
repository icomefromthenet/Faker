<?php
namespace Faker\Components\Faker;

use Symfony\Component\EventDispatcher\Event,
    Faker\Components\Faker\Composite\CompositeInterface;

/**
  *  Event is used for events found in \Faker\Components\Faker\BuildEvents
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.3
  */
class BuildEvent extends Event
{
    /**
      *  @var  Faker\Components\Faker\Builder
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
      * @param Builder $builder instance of builder
      * @param string $info an info string
      */
    public function __construct(Builder $builder,$info)
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
      *  @return Builder
      */
    public function getBuilder()
    {
        return $this->builder;
    }
}
/*End of File */
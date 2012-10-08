<?php
namespace Faker\Components\Faker;

use Faker\Components\Faker\Exception as FakerException,
    Symfony\Component\EventDispatcher\EventSubscriberInterface,
    Symfony\Component\EventDispatcher\EventDispatcherInterface,
    Symfony\Component\Console\Output\OutputInterface;

/**
  *  Send output to console for builder events
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.3
  */
class BuilderConsoleOutput implements EventSubscriberInterface
{
    
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */
    protected $event;
    
    /**
      *  @var Symfony\Component\Console\Output\OutputInterface 
      */
    protected $output;
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Fetch Format Event to listen to
      *
      *  @return mixed[]
      *  @access public
      */
    static public function getSubscribedEvents()
    {
        return array(
            BuildEvents::onBuildingStart    => array('onBuildingStart', 0),
            BuildEvents::onBuildingEnd      => array('onBuildingEnd', 0),
            BuildEvents::onCompileStart     => array('onCompileStart', 0),
            BuildEvents::onCompileEnd       => array('onCompileEnd',0),
            BuildEvents::onValidationStart  => array('onValidationStart',0),
            BuildEvents::onValidationEnd    => array('onValidationEnd',0),
        );
    }
    
    //  -------------------------------------------------------------------------
    # Constructor
    
    /**
      *  class constructor
      *
      *  @param EventDispatcherInterface $event
      *  @access OutputInterface $out
      */
    public function __construct(EventDispatcherInterface $event, OutputInterface $out )
    {
        $this->event  = $event;
        $this->output = $out;
        
    }
    
    //  -------------------------------------------------------------------------
    # Build Events
    
    public function onBuildingStart(BuildEvent $event)
    {
        $this->output->writeln($event->getInfo());
    }
   
    public function onBuildingEnd(BuildEvent $event)
    {
        $this->output->writeln($event->getInfo());
    }
    
    public function onCompileStart(BuildEvent $event)
    {
        $this->output->writeln($event->getInfo());
    }
    
    public function onCompileEnd(BuildEvent $event)
    {
        $this->output->writeln($event->getInfo());
    }
    
    public function onValidationStart(BuildEvent $event)
    {
        $this->output->writeln($event->getInfo());
    }
    
    public function onValidationEnd(BuildEvent $event)
    {
        $this->output->writeln($event->getInfo());
    }
        
}
/* End of File */
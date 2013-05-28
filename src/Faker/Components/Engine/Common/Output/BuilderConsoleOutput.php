<?php
namespace Faker\Components\Engine\Common\Output;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;
use Faker\Components\Engine\Common\BuildEvents;
use Faker\Components\Engine\Common\BuildEvent;


/**
  *  Send output to console for builder events
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.3
  */
class BuilderConsoleOutput implements ConsoleOutputInterface
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
    
    //  -------------------------------------------------------------------------
    # Format Events
    
    
    /**
      *  Handles Event FormatEvents::onSchemaStart
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaStart(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onSchemaEnd
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaEnd(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableStart
      *
      *  @param GenerateEvent $event
      */
    public function onTableStart(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableEnd
      *
      *  @param GenerateEvent $event
      */
    public function onTableEnd(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowStart
      *
      *  @param GenerateEvent $event
      */
    public function onRowStart(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowEnd
      *
      *  @param GenerateEvent $event
      */
    public function onRowEnd(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onColumnStart
      *
      *  @param GenerateEvent $event
      */
    public function onColumnStart(GenerateEvent $event)
    {
        
    }
    
    /**
      *  Handles Event FormatEvents::onColumnGenerate
      *
      *  @param GenerateEvent $event
      */
    public function onColumnGenerate(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onColumnEnd
      *
      *  @param GenerateEvent $event
      */
    public function onColumnEnd(GenerateEvent $event)
    {
        
        
    }
    
     # Output Interface
    
      /**
     *  Return the symfony console
     *
     *  @access public
     *  @return Symfony\Component\Console\Output\OutputInterface
     *
    */    
    public function getConsole()
    {
        return $this->console;
    }
    
    /**
     *  Sets the console        
     *
     *  @access public
     *  @return void
     *  @param Symfony\Component\Console\Output\OutputInterface $console
     *
    */
    public function setConsole(OutputInterface $console)
    {
        $this->console = $console;
    }
}
/* End of File */
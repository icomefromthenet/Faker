<?php
namespace Faker\Components\Engine\Entity\Event;

use Zend\ProgressBar\ProgressBar;
use Zend\ProgressBar\Adapter\Console as ZendConsoleAdapter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Output\ConsoleOutputInterface;
use Faker\Components\Engine\Common\BuildEvents;
use Faker\Components\Engine\Common\BuildEvent;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;

/**
  *  Output the Events found under BuildEvents and FormatEvents to the using progressbar
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ProgressBarOut implements ConsoleOutputInterface
{
    
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface 
      */
    protected $event;
    
    /**
      *  @var Zend\ProgressBar\ProgressBar 
      */
    protected $bar;
    
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
            FormatEvents::onSchemaStart    => array('onSchemaStart', 0),
            FormatEvents::onSchemaEnd       => array('onSchemaEnd', 0),
            FormatEvents::onTableStart     => array('onTableStart',0),
            FormatEvents::onTableEnd       => array('onTableEnd',0),
            FormatEvents::onRowStart       => array('onRowStart',0),
            FormatEvents::onRowEnd         => array('onRowEnd',0),
            FormatEvents::onColumnStart    => array('onColumnStart',0),
            FormatEvents::onColumnGenerate => array('onColumnGenerate',0),
            FormatEvents::onColumnEnd      => array('onColumnEnd',0),
        
        );
    }
    
    //  -------------------------------------------------------------------------
    # Constructor
    
    /**
      *  class constructor
      *
      *  @param EventDispatcherInterface $event
      */
    public function __construct(EventDispatcherInterface $event, ProgressBar $bar )
    {
        $this->event = $event;
        $this->bar = $bar;
        
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
        $this->bar->finish();
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
        return null;
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowEnd
      *
      *  @param GenerateEvent $event
      */
    public function onRowEnd(GenerateEvent $event)
    {
        $this->bar->next(1,'next entity');
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
    
    //-------------------------------------------------------
    # ConsoleOuputInterface
    
    /**
     *  Return the symfony console
     *
     *  @access public
     *  @return Symfony\Component\Console\Output\OutputInterface
     *
    */    
    public function getConsole()
    {
        
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
        
    }
    
    
    //  -------------------------------------------------------------------------
    # Build Events
    
    /**
     *  Handle Event BuildEvents::onBuildingStart
     *
     *  @access public
     *  @return void
     *  @param BuildEvent $event
     *
    */
    public function onBuildingStart(BuildEvent $event)
    {
        
    }
    
    /**
     *  Handle Event BuildEvents::onBuildingEnd
     *
     *  @access public
     *  @return void
     *  @param BuildEvent $event
     *
    */
    public function onBuildingEnd(BuildEvent $event)
    {
        
    }
    
    /**
     *  Handle Event BuildEvents::onCompileStart
     *
     *  @access public
     *  @return void
     *  @param BuildEvent $event
     *
    */
    public function onCompileStart(BuildEvent $event)
    {
        
    }
    
    /**
     *  Handle Event BuildEvents::onCompileEnd
     *
     *  @access public
     *  @return void
     *  @param BuildEvent $event
     *
    */
    public function onCompileEnd(BuildEvent $event)
    {
        
    }
    
    /**
     *  Handle Event BuildEvents::onValidationStart
     *
     *  @access public
     *  @return void
     *  @param BuildEvent $event
     *
    */
    public function onValidationStart(BuildEvent $event)
    {
        
    }
    
    /**
     *  Handle Event BuildEvents::onValidationEnd
     *
     *  @access public
     *  @return void
     *  @param BuildEvent $event
     *
    */
    public function onValidationEnd(BuildEvent $event)
    {
        
    }
}
/* End of File */
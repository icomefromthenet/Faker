<?php
namespace Faker\Components\Engine\Common\Output;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;
use Faker\Components\Engine\Common\BuildEvents;
use Faker\Components\Engine\Common\BuildEvent;

/**
  *  Output the Events found under BuildEvents and FormatEvents to the cli
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
interface ConsoleOutputInterface extends EventSubscriberInterface
{
    
    /**
     *  Return the symfony console
     *
     *  @access public
     *  @return Symfony\Component\Console\Output\OutputInterface
     *
    */    
    public function getConsole();
    
    /**
     *  Sets the console        
     *
     *  @access public
     *  @return void
     *  @param Symfony\Component\Console\Output\OutputInterface $console
     *
    */
    public function setConsole(OutputInterface $console);
    
    
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
    public function onBuildingStart(BuildEvent $event);
    
    /**
     *  Handle Event BuildEvents::onBuildingEnd
     *
     *  @access public
     *  @return void
     *  @param BuildEvent $event
     *
    */
    public function onBuildingEnd(BuildEvent $event);
    
    /**
     *  Handle Event BuildEvents::onCompileStart
     *
     *  @access public
     *  @return void
     *  @param BuildEvent $event
     *
    */
    public function onCompileStart(BuildEvent $event);
    
    /**
     *  Handle Event BuildEvents::onCompileEnd
     *
     *  @access public
     *  @return void
     *  @param BuildEvent $event
     *
    */
    public function onCompileEnd(BuildEvent $event);
    
    /**
     *  Handle Event BuildEvents::onValidationStart
     *
     *  @access public
     *  @return void
     *  @param BuildEvent $event
     *
    */
    public function onValidationStart(BuildEvent $event);
    
    /**
     *  Handle Event BuildEvents::onValidationEnd
     *
     *  @access public
     *  @return void
     *  @param BuildEvent $event
     *
    */
    public function onValidationEnd(BuildEvent $event);
    
    
    //  -------------------------------------------------------------------------
    # Format Events
    
    
    /**
      *  Handles Event FormatEvents::onSchemaStart
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaStart(GenerateEvent $event);
    
    
    /**
      *  Handles Event FormatEvents::onSchemaEnd
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaEnd(GenerateEvent $event);
    
    
    /**
      *  Handles Event FormatEvents::onTableStart
      *
      *  @param GenerateEvent $event
      */
    public function onTableStart(GenerateEvent $event);
    
    
    /**
      *  Handles Event FormatEvents::onTableEnd
      *
      *  @param GenerateEvent $event
      */
    public function onTableEnd(GenerateEvent $event);
    
    
    /**
      *  Handles Event FormatEvents::onRowStart
      *
      *  @param GenerateEvent $event
      */
    public function onRowStart(GenerateEvent $event);
    
    
    /**
      *  Handles Event FormatEvents::onRowEnd
      *
      *  @param GenerateEvent $event
      */
    public function onRowEnd(GenerateEvent $event);
    
    
    /**
      *  Handles Event FormatEvents::onColumnStart
      *
      *  @param GenerateEvent $event
      */
    public function onColumnStart(GenerateEvent $event);
    
    /**
      *  Handles Event FormatEvents::onColumnGenerate
      *
      *  @param GenerateEvent $event
      */
    public function onColumnGenerate(GenerateEvent $event);
    
    
    /**
      *  Handles Event FormatEvents::onColumnEnd
      *
      *  @param GenerateEvent $event
      */
    public function onColumnEnd(GenerateEvent $event);
    
}

/* End of File */

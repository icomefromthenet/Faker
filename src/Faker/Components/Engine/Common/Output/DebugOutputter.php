<?php
namespace Faker\Components\Engine\Common\Output;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;
use Faker\Components\Engine\Common\BuildEvents;
use Faker\Components\Engine\Common\BuildEvent;

/**
  *  Output the Events found under BuildEvents and FormatEvents to monitor memeory usage
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class DebugOutputter implements ConsoleOutputInterface
{
    /**
      *  @var Symfony\Component\Console\Output\ConsoleOutputInterface 
      */
    protected $output;

    /**
      *  @var integer a count for current row 
      */
    protected $row = 0;
    

    //  -------------------------------------------------------------------------
    
    
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;    
    }
    
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
            FormatEvents::onSchemaEnd      => array('onSchemaEnd', 0),
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
    
    /**
      *  Format a memory string
      *
      *  @param integer memory
      *  @return string the formatted memory
      */
    public function formatMemory($memory)
    {
        if ($memory < 1024) 
            $memory = $memory." bytes"; 
        elseif ($memory < 1048576) 
            $memory = round($memory/1024,2)." kilobytes"; 
        else 
            $memory = round($memory/1048576,2)." megabytes"; 
            
        return $memory;
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
        $schema_name = $event->getId();
        $memory      = $this->formatMemory(memory_get_usage());
        $peak        = $this->formatMemory(memory_get_peak_usage());
        
        $this->output->writeln(sprintf('Writing Schema %s <info> memory_start= %s </info> <comment> memory_peak= %s </comment>',$schema_name,$memory,$peak));   
        $this->row = 0;
    }
    
    
    /**
      *  Handles Event FormatEvents::onSchemaEnd
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaEnd(GenerateEvent $event)
    {
        $schema_name = $event->getId();
        $memory      = $this->formatMemory(memory_get_usage());
        $peak        = $this->formatMemory(memory_get_peak_usage());
        
        $this->output->writeln(sprintf('Finished Writing Schema %s <info> memory_start= %s </info> <comment> memory_peak= %s </comment>',$schema_name,$memory,$peak));   
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableStart
      *
      *  @param GenerateEvent $event
      */
    public function onTableStart(GenerateEvent $event)
    {
        $table_name  = $event->getId();
        $memory      = $this->formatMemory(memory_get_usage());
        $peak        = $this->formatMemory(memory_get_peak_usage());
        
        $this->output->writeln(sprintf('Writing Table %s <info> memory_start= %s </info> <comment> memory_peak= %s</comment>',$table_name,$memory,$peak));   
        $this->row = 0;
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableEnd
      *
      *  @param GenerateEvent $event
      */
    public function onTableEnd(GenerateEvent $event)
    {
        $table_name = $event->getId();
        $memory      = $this->formatMemory(memory_get_usage());
        $peak        = $this->formatMemory(memory_get_peak_usage());
        
        $this->output->writeln(sprintf('Finished Writing Table %s <info> memory_start= %s </info> <comment> memory_peak= %s </comment>',$table_name,$memory,$peak));   
        $this->row = 0;
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowStart
      *
      *  @param GenerateEvent $event
      */
    public function onRowStart(GenerateEvent $event)
    {
        $this->row = $this->row +1;
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowEnd
      *
      *  @param GenerateEvent $event
      */
    public function onRowEnd(GenerateEvent $event)
    {
        $memory      = $this->formatMemory(memory_get_usage());
        $peak        = $this->formatMemory(memory_get_peak_usage());
        
        $this->output->writeln(sprintf('Writing Row %s <info> memory_start= %s </info> <comment>memory_peak= %s </comment>',$this->row,$memory,$peak));   
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
    
    //-------------------------------------------------------
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
        return $this->output;
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
        $this->output = $console;
    }
    
}
/* End of File */
<?php
namespace Faker\Components\Engine\Common\Formatter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Faker\PlatformFactory;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Formatter\FormatterFactory;
use Faker\Components\Engine\Common\Builder\NodeCollection;

/**
  *  Factory for Formatters
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class FormatterBuilder extends NodeCollection
{
    /**
      *  @var Symfony\Component\EventDispatcher\EventDispatcherInterface;
      */
    protected $eventDispatcher;
    
    /**
      *  @var  Faker\Components\Engine\Common\Formatter\FormatterFactory
      */
    protected $formatterFactory;
    
    /**
      *  @var Faker\PlatformFactory 
      */
    protected $platformFactory;
    
     /**
      *  Class Constructor
      *
      *  @access public
      *  @return void
      */
    public function __construct(EventDispatcherInterface $event, FormatterFactory $formatterFactory, PlatformFactory $platformFactory)
    {
        
        $this->eventDispatcher   = $event;
        $this->formatterFactory  = $formatterFactory;
        $this->platformFactory   = $platformFactory;
        
    }
    
    
    public function getNode()
    {
        return null;
    }
 
    public function end()
    {
        $parent   = $this->getParent();
        $children = $this->children();
        
        foreach($children as $formatter) {
            $parent->append($formatter);
        }
        
        return $parent;
    }
    
    //------------------------------------------------------------------
    # Builders
    
    /**
      *  Returns a Custom Formatter Definition
      *
      *  @return CustomFormatterDefinition
      *  @access public
      */
    public function customFormatter()
    {
        $formatter = new CustomFormatterDefinition($this->eventDispatcher,$this->formatterFactory,$this->platformFactory);
        $formatter->setParent($this);
        
        return $formatter;
    }
    
    /**
      *   Returns a Phpunit Formatter Definition
      *
      *   @access public
      *   @return PhpunitFormatterDefinition
      */
    public function phpUnitFormatter()
    {
        $formatter = new PhpunitFormatterDefinition($this->eventDispatcher,$this->formatterFactory,$this->platformFactory);
        
        $formatter->setParent($this);
        
        return $formatter;
    }
    
    /**
      * Return a SQL Formatter Definition
      *
      * @access public
      * @return SqlFormatterDefinition
      */
    public function sqlFormatter()
    {
        $formatter = new SqlFormatterDefinition($this->eventDispatcher,$this->formatterFactory,$this->platformFactory);
        $formatter->setParent($this);
        
        return $formatter;
    }

}
/* End of File */
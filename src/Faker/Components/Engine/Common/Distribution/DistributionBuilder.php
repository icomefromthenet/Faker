<?php
namespace Faker\Components\Engine\Common\Distribution;

use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Templating\Loader;
use Faker\Components\Engine\EngineException;
use Faker\Locale\LocaleInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\DBAL\Connection;
use PHPStats\Generator\GeneratorInterface;

/**
  *  Factory for Distribution Builders
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class DistributionBuilder
{
    
    protected $parent;
    
    protected $utilities;
    
    protected $locale;
    
    protected $generator;
    
    protected $eventDispatcher;
    
    protected $database;
    
    protected $templateLoader;
    
    
    
     /**
      *  Class Constructor
      *
      *  @access public
      *  @return void
      */
    public function __construct(EventDispatcherInterface $event,
                                Utilities $util,
                                GeneratorInterface $generator,
                                LocaleInterface $locale,
                                Connection $conn,
                                Loader $loader
                                )
    {
        
        $this->eventDispatcher = $event;
        
        $this->database        = $conn;
        $this->generator       = $generator;
        $this->utilities       = $util;
        $this->locale          = $locale;
        $this->templateLoader  = $loader;
    }
    
    
    
    public function exponentialDistribution()
    {
        $field = new ExponentialDistributionDefinition();
        
        $field->generator($this->generator);
        $field->utilities($this->utilities);
        $field->database($this->database);
        $field->locale($this->locale);
        $field->eventDispatcher($this->eventDispatcher);
        $field->templateLoader($this->templateLoader);
        
        return $field;
    }
    
    
    public function normalDistribution()
    {
        $field = new NormalDistributionDefinition();
        
        $field->generator($this->generator);
        $field->utilities($this->utilities);
        $field->database($this->database);
        $field->locale($this->locale);
        $field->eventDispatcher($this->eventDispatcher);
        $field->templateLoader($this->templateLoader);
        
        return $field;
    }
    
    /**
      *  Loads the Possion Distrubution builder
      *
      *  @access public
      */
    public function poissonDistribution()
    {
        $field = new PoissonDistributionDefinition();
        
        $field->generator($this->generator);
        $field->utilities($this->utilities);
        $field->database($this->database);
        $field->locale($this->locale);
        $field->eventDispatcher($this->eventDispatcher);
        $field->templateLoader($this->templateLoader);
        
        return $field;
    }

}
/* End of File */
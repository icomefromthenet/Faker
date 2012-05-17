<?php
namespace Faker\Components\Faker;

use Faker\Project;
use Faker\Components\ManagerInterface;
use Faker\Io\IoInterface;

use Faker\PlatformFactory;
use Faker\ColumnTypeFactory;
use Faker\Components\Faker\Formatter\FormatterFactory;
use Faker\Components\Faker\SchemaAnalysis;


class Manager implements ManagerInterface
{

    protected $loader;

    protected $writer;

    protected $io;

    /**
      *  @var Faker\Project 
      */
    protected $project;
    
    //  -------------------------------------------------------------------------
    # Class Constructor

    /*
     * __construct()
     * @param $arg
     */

     /**
       *  function __construct
       *
       *  class constructor
       *
       *  @access public
       */
    public function __construct(IoInterface $io, Project $di)
    {
        $this->io = $io;
        $this->project = $di;
    }


    //  -------------------------------------------------------------------------
    # Congfig file loader

    public function getLoader()
    {
        throw new RuntimeException('not implemented');
    }

    //  -------------------------------------------------------------------------
    # Config Writter

    /**
      * function getWriter
      *
      * @access public
      * @return \Faker\Components\Config\Writer
      */
    public function getWriter()
    {
        return $this->project['writer_manager'];     
    }
    
    //  -------------------------------------------------------------------------

    /**
      *  Create a new Doctrine Platform Factory
      *
      *  @access public
      *  @return \Faker\PlatformFactory
      */    
    public function getPlatformFactory()
    {
        return new PlatformFactory();
    }
    
    /**
      *  Create a new Doctrine Column Type Factory
      *
      *  @access public
      *  @return Faker\ColumnTypeFactory
      */
    public function getColumnTypeFactory()
    {
        return new ColumnTypeFactory();
        
    }
    
    /**
      *  Create a new Formatter Factory
      *  
      *  @access public
      *  @return Faker\Components\Faker\Formatter\FormatterFactory
      */
    public function getFormatterFactory()
    {
        return new FormatterFactory($this->project['event_dispatcher'],
                                    $this->getWriter());   
    }
    
    /**
      *  Load the xml schema parser
      *
      *  @access public
      *  @return \Faker\Componenets\Faker\SchemaParser
      */    
    public function getSchemaParser()
    {
        return new SchemaParser($this->getCompositeBuilder());    
    }
    
    /**
      *  Loads the schema analyser
      *
      *  @access public
      *  @return \Faker\Components\Faker\SchemaAnalysis
      */
    public function getSchemaAnalyser()
    {
        return new SchemaAnalysis();
    }
    
    
    
    /**
      *  Create a new composite builder
      *
      *  @return Faker\Components\Faker\Builder
      *  @access public
      */    
    public function getCompositeBuilder()
    {
        return new Builder($this->project['event_dispatcher'],
                           $this->getPlatformFactory(),
                           $this->getColumnTypeFactory(),
                           $this->getTypeFactory(),
                           $this->getFormatterFactory());        
    }
    
    /**
      *  Create a new type factory
      *
      *  @access public
      *  @return Faker\Components\Faker\TypeFactory
      */
    public function getTypeFactory()
    {
        return new TypeFactory(new Utilities($this->project),$this->project['event_dispatcher']);        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  Loads the component IO
      *
      *  @access public
      *  @return Faker\Io\IoInterface
      */    
    public function getIo()
    {
        return $this->io;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */

<?php
namespace Faker\Components\Faker;

use Faker\Project,
    Faker\Components\ManagerInterface,
    Faker\Io\IoInterface,
    Faker\PlatformFactory,
    Faker\ColumnTypeFactory,
    Faker\Components\Faker\Formatter\FormatterFactory,
    Faker\Components\Faker\SchemaAnalysis,
    Faker\Components\Faker\Compiler\Pass\CircularRefPass,
    Faker\Components\Faker\Compiler\Pass\CacheInjectorPass,
    Faker\Components\Faker\Compiler\Pass\KeysExistPass,
    Faker\Components\Faker\Compiler\Pass\GeneratorInjectorPass,
    Faker\Components\Faker\Compiler\Pass\LocalePass,
    Faker\Components\Faker\Compiler\Pass\TopOrderPass,
    Faker\Components\Faker\Compiler\Graph\DirectedGraph,
    Faker\Components\Faker\Visitor\DirectedGraphVisitor,
    Faker\Components\Faker\Visitor\LocaleVisitor,
    Faker\Components\Faker\Compiler\Compiler;

class Manager implements ManagerInterface
{

    protected $loader;

    protected $writer;

    protected $io;

    /**
      *  @var boolean use the circular reference compiler check
      */
    protected $use_c_check = false;
    
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
        $compiler_pass = array(new KeysExistPass(),new CacheInjectorPass());
        
        if($this->use_c_check === true) {
            $compiler_pass[] =  new CircularRefPass();
            $compiler_pass[] =  new TopOrderPass();
        }
        $compiler_pass[] = new GeneratorInjectorPass($this->project['generator_factory'],$this->project['random_generator']);
        $compiler_pass[] = new LocalePass(new LocaleVisitor($this->project->getLocaleFactory()));
        
        return new Builder($this->project['event_dispatcher'],
                           $this->getPlatformFactory(),
                           $this->getColumnTypeFactory(),
                           $this->getTypeFactory(),
                           $this->getFormatterFactory(),
                           new Compiler(new DirectedGraphVisitor(new DirectedGraph())),
                           $compiler_pass
                           );        
    }
    
    /**
      *  Create a new type factory
      *
      *  @access public
      *  @return Faker\Components\Faker\TypeFactory
      */
    public function getTypeFactory()
    {
        return new TypeFactory(new Utilities($this->project),
                               $this->project['event_dispatcher'],
                               $this->project['random_generator']
                               );        
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
    # Circular Reference Compiler Check    
    
    /**
      *   Enable to Circular reference compiler check
      *
      *   @return void
      *   @access public
      */
    public function enableCRCheck()
    {
        $this->use_c_check = true;
    }
    
    /**
      *  Disable to circular reference compiler check
      *
      *  @return void
      *  @access public
      */
    public function disableCRCheck()
    {
        $this->use_c_check = false;
    }
    
    //  ----------------------------------------------------------------------------
    
}
/* End of File */

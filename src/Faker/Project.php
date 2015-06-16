<?php
namespace Faker;

use  Pimple;
use  Symfony\Component\Console\Output\OutputInterface;
use  Symfony\Component\EventDispatcher\EventDispatcherInterface;
use  Symfony\Component\EventDispatcher\EventDispatcher;
use  PHPStats\Generator\GeneratorInterface;
use  Symfony\Component\Finder\Finder;
use  Faker\Exception as FakerException;
use  Faker\Components\Engine\DB\Builder\SchemaBuilder;
use  Faker\Components\Engine\Common\Formatter\FormatEvents;
use  Faker\ChannelEventDispatcher;
use  Faker\Locale\LocaleInterface;
use  Faker\Components\Engine\Common\Utilities;
use  Faker\Components\Engine\Common\Composite\TableNode;



/**
  *  Di Container for A Project
  *
  *  @since 1.0.0
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  * 
  */
class Project extends Pimple
{

   /**
    * @var arrar[SchemaBuilder]
    */ 
    protected $builderCollection = array();
    
    

    //--------------------------------------------------------------------------
    # Schema Builder
    
    /**
     * Attach event to FormatEvents::onSchemaStart to the
     * global event dispatcher will execue once for each schema and once for global
     * 
     * @access public 
     * @return void
     * @param Closure   $closure
     */  
    public function onGlobalGenerateStart($closure)
    {
        $this->getEventDispatcher()->addListener(FormatEvents::onSchemaStart,$closure);
        
        return $this;
    }
    
    /**
     * Attach event to FormatEvents::onSchemaEnd to the
     * global event dispatcher will execue once for each schema and once for global
     * 
     * @access public 
     * @return void
     * @param Closure   $closure
     */ 
    public function onGlobalGenerateEnd($closure)
    {
        $this->getEventDispatcher()->addListener(FormatEvents::onSchemaEnd,$closure);
        
        return $this;
    }
    
    /**
     * Add a Pass to this project
     * 
     *  @param string   $passName   A unique name for this pass 
     *  @param Faker\Project the DI container
     *  @param string $name of the entity
     *  @param Faker\Locale\LocaleInterface $locale to use
     *  @param Faker\Components\Engine\Common\Utilities  $util
     *  @param PHPStats\Generator\GeneratorInterface $util
     */ 
    public function addPass($passName,$name,LocaleInterface $locale = null,Utilities $util = null,GeneratorInterface $gen = null) 
    {
        
        if(false === empty($passName) && false === isset($this->builderCollection[$passName])) {
            $this->builderCollection[] = $this->create($name,$local,$util,$gen);
        } else {
            throw new FakerException(sprintf('The %s is already set in this project or empty value been passed',$passName));
        }
        
        return $this->builderCollection[$passName];
        
    }
    
    /**
      *  Static Constructor
      *
      *  @param Faker\Project the DI container
      *  @param string $name of the entity
      *  @param Faker\Locale\LocaleInterface $locale to use
      *  @param Faker\Components\Engine\Common\Utilities  $util
      *  @param PHPStats\Generator\GeneratorInterface $util
      */
    public function create($name,LocaleInterface $locale = null,Utilities $util = null,GeneratorInterface $gen = null)
    {
        
        # set the event classes
        $defaultEventDispatcher   = $this->getEventDispatcher();
        $newChannelDispatcher     = new ChannelEventDispatcher($defaultEventDispatcher);
        $newChannelDispatcher->addChannel($name,new EventDispatcher());
        $newChannelDispatcher->switchChannel($name);
        
        # fetch other dep from continer
        $repo               = $this->getEngineTypeRepository();
        $conn               = $this->getGeneratorDatabase();
        $loader             = $this->getTemplatingManager()->getLoader();
        $platformFactory    = $this->getDBALPlatformFactory();
        $formatterFactory   = $this->getFormatterFactory($newChannelDispatcher);
        $compiler           = $this->getEngineCompiler();
        $datasourceRepo     = $this->getDatasourceRepository();
        
        if($locale === null) {
            $locale = $this->getLocaleFactory()->create('en');
        }
        
        if($util === null) {
            $util = $this->getEngineUtilities();
        }
        
        if($gen === null) {
            $gen = $this->getDefaultRandom();
        }
        
        $builder =  new SchemaBuilder($name,$newChannelDispatcher,$repo,$locale,$util,$gen,$conn,$loader,$platformFactory,$formatterFactory,$compiler,$datasourceRepo);
       
        
        return $builder;
    }
    
    
    /**
     * Start generation runs for all constructed schema objects
     * 
     * @return void
     * @access public
     */ 
    public function generate()
    {
        foreach($this->builderCollection as $builder) {
            $result = array();
            $builder->getNode()->generate(0,$result,array());
        }
        
        return $this;    
    }
    
    /**
     * Find the total number of rows that will be generated
     * 
     * @return integer
     * @access public
     */ 
    public function howManyRows()
    {
        $rows = 0;
        
        foreach($this->builderCollection as $builder) {
             $composite = $builder->getNode();
             
            foreach($composite->getChildren() as $table) {
                if($table instanceof TableNode) {
                    $rows +=  $table->getRowsToGenerate();
                }
            }
        }
        
        return $rows;
        
    }
    
    /**
     * Clear the builder collection, used in testing
     * 
     * @access public
     * @return void
     */ 
    public function clearBuilderCollection()
    {
        $this->builderCollection = array();
    }
    
    //--------------------------------------------------------------------------
    # Node interface && ParentNodeInterface
    
    
    /**
    * Return the parent node and build the node
    * defined by this builder and append it to the parent.
    *
    * @return NodeInterface The builder of the parent node
    */
    public function end()
    {
        return $this;
    }
    
    //--------------------------------------------------------------------------
    # DI Stuff
    
    
    /**
      *  function getPath
      *
      *  @return \Faker\Path
      *  @access public
      */
    public function getPath()
    {
         return $this['project_path'];
    }
     
    /**
      *  function setPath
      *
      *  @access public
      *  @param \Faker\Path
      */ 
    public function setPath(Path $path)
    {
        $this['project_path'] = $path;  
    }
    
    
    /**
      *  Function getDataPath
      *
      *  @return \Faker\Path
      *  @access public
      */
    public function getDataPath()
    {
          return $this['data_path'];
     
    }
    
    

    //  -------------------------------------------------------------------------
    # Constructor

    /**
     * Function __construct
     *
     * Class Constructor
     *
     *  @return void
     *  @param \Faker\path $path
     */
    public function __construct(\Faker\Path $path)
    {
        $this['project_path'] = $path;
        $this['default'] = 'default';
        $this['schema_name'] = 'default';
    }


    //  -------------------------------------------------------------------------
    # Creats a new project


    public function build(\Faker\Io\Io $folder, \Faker\Io\Io $skelton, \Symfony\Component\Console\Output\OutputInterface $output)
    {

        $mode = 0777;

        #check if root folder exists
        if(is_dir($folder->getBase()) === false) {
            throw new FakerException('Root directory does not exist');
        }

        #make config folders
        $config_path = $folder->getBase() . DIRECTORY_SEPARATOR .'config';

        if (mkdir($config_path,$mode) === TRUE) {
                $output->writeln('<info>Created Config Folder</info>');

                //copy files into it
                $files = $skelton->iterator('config');

                foreach($files as $file){
                    if($this->copy($file,$config_path) === TRUE) {
                       $output->writeln('++ Copied '.basename($file));
                    }

                }

            }


            #make template folder
            $template_path = $folder->getBase() . DIRECTORY_SEPARATOR . 'template';
            if (mkdir($template_path,$mode) === TRUE) {
                $output->writeln('<info>Created Template Folder</info>');

                 //copy files into it
                $files = $skelton->iterator('template');


                foreach($files as $file){
                    if($this->copy($file,$template_path) === TRUE) {
                       $output->writeln('++ Copied '.basename($file));
                    }

                }


            }
            
             #make extension extension folder
            $extension_path = $folder->getBase() . DIRECTORY_SEPARATOR . 'extension';
            if (mkdir($extension_path,$mode) === TRUE) {
                $output->writeln('<info>Created Extension Folder</info>');

                 //copy files into it
                $files = $skelton->iterator('extension');

                foreach($files as $file){
                    if($this->copy($file,$extension_path) === TRUE) {
                          $output->writeln('++ Copied '.basename($file));
                    }
                }


            }
            
            #make dump folder
            $dump_path = $folder->getBase() . DIRECTORY_SEPARATOR . 'dump';
            if (mkdir($dump_path,$mode) === TRUE) {
                $output->writeln('<info>Created Dump Folder</info>');

            }
            
          #make sources folder
          $sources_path = $folder->getBase() . DIRECTORY_SEPARATOR . 'sources';
          if (mkdir($sources_path,$mode) === TRUE) {
               $output->writeln('<info>Created Sources Folder</info>');
               
                 //copy files into it
                $files = $skelton->iterator('sources');

                foreach($files as $file){
                    if($this->copy($file,$sources_path) === TRUE) {
                          $output->writeln('++ Copied '.basename($file));
                    }
                }

          }
            

    }

    //-----------------------------------------------------------------------------

    /**
     * Copy a path to destination, check if file,directory or link
     * @param string $source      The Source File
     * @param string $destination The Destination File
     * @return boolean
     */
    public function copy(\Symfony\Component\Finder\SplFileInfo $source,$destination)
    {
        $new_path = $destination . DIRECTORY_SEPARATOR. $source->getRelativePathname();

        #Test if Source is a link
        if($source->isLink()) {
           return symlink($source,$new_path);
        }

        # Test if source is a directory
        if($source->isDir()){
            return mkdir($new_path);
        }

        #Test if Source is a file
        if($source->isFile()) {
            return copy($source,$new_path);

        }

        return FALSE;

    }

    //  -----------------------------------------------------------------------------
    # Database

    /**
      *  function getDatabase
      *
      *  @access public
      *  @return  the configured database handler
      */
    public function getDatabase()
    {
        return $this['database'];
    }
    
    /**
     *  Return the database connection pool
     * 
     * @access public
     * @return Faker\Components\Config\ConnectionPool
     */ 
    public function getConnectionPool()
    {
        return $this['connection_pool'];
    }
   
    /**
      * Fetch a faker database
      *
      * @access public
      * @return \Doctine\DBAL\Connection
      */
    public function getGeneratorDatabase()
    {
        return $this['faker_database'];
    }
   

    //  -------------------------------------------------------------------------
    # Manager loaders


    /**
      *  function getConfigManager
      *
      *  @access public
      *  @return \Faker\Components\Config\Manager an instance of the config component manager
      */
    public function getConfigManager()
    {
        return $this['config_manager'];
    }

   
    /**
      *  function getTemplateManager
      *
      *  @access public
      *  @return \Faker\Components\Templating\Manager an instance of the templating component manager
      */
    public function getTemplatingManager()
    {
        return $this['template_manager'];
    }

    
    /**
      *  function getFakerManager
      *
      *  @access public
      *  @return \Faker\Components\Engine\Original\Manager an instance of the Faker component manager
      */
    public function getWriterManager()
    {
        return $this['writer_manager'];
    }    
    

    //  -------------------------------------------------------------------------
    # Debug Log

    /**
      *  function getLogger
      *
      *  @access public
      *  @return \Monolog\Logger an instance of the debug logger
      */
    public function getLogger()
    {
        return $this['logger'];
    }

    //  -------------------------------------------------------------------------
    # Config Name

    /**
      * function getConfigName
      *
      * @access public
      * @return string the name of the config file to use
      */
    public function getConfigName()
    {
        return  \Faker\Components\Config\Loader::DEFAULTNAME . \Faker\Components\Config\Loader::EXTENSION;
    }


     //------------------------------------------------------------------

     /**
      *  Function getConfigFile
      *
      *  @access public
      *  @return \Migration\Components\Config\Entity
      */ 
    public function getConfigFile()
    {
          return $this['config_file'];
    }
    
    public function hasConfigSet()
    {
          return $this['has_config'];
    }
   
   
     //  ----------------------------------------------------------------------------
     # Others
     
     /**
      *  Return an engine compiler
      *
      *  @access public
      *  @return Faker\Components\Engine\XML\Parser\SchemaAnalysis
      *
     */
     public function getSchemaAnalyser()
     {
          return  $this['engine_xml_schema_analyser'];
     }
     
     /**
      *  Return an engine compiler
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Compiler\CompilerInterface
      *
     */
     public function getEngineCompiler()
     {
          return  $this['engine_common_compiler'];      
     }
     
     /**
      *  Return an engine compiler
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Compiler\CompilerInterface
      *
     */
     public function getXMLEngineCompiler()
     {
          return  $this['engine_xml_compiler'];      
     }
     
     
     public function getXMLEngineParser()
     {
          return $this['engine_xml_parser'];
     }
     
     /**
      *  Return an engine compiler
      *
      *  @access public
      *  @return Faker\Components\Engine\XML\Builder\NodeBuilder
      *
     */
     public function getXMLEngineBuilder()
     {
          return  $this['engine_xml_builder'];      
     }
     
     
     /**
       *  Fetch the Simple String Factory
       *
       *  @access public
       *  @return Faker\Text\StringFactoryInterface
       */
     public function getSimpleStringFactory()
     {
          return $this['simplestring_factory'];
     }
     
     /**
       *  Fetch the Locale Factory
       *
       *  @access public
       *  @return Faker\Locale\LocaleFactory
       */
     public function getLocaleFactory()
     {
          return $this['locale_factory'];
     }
     
      /**
       *  Fetch the Default Locale
       *
       *  @access public
       *  @return Faker\Locale\LocaleInterface
       */
     public function getDefaultLocale()
     {
          return $this['default_locale'];
     }
   
     /**
       *  Fetch the Default Locale
       *
       *  @access public
       *  @return PHPStats\Generator\GeneratorInterface
       */
     public function getDefaultRandom()
     {
          return $this['random_generator'];          
     }
     
     /**
       *  Fetch engine common utilities
       *
       *  @access public
       *  @return Faker\Components\Engine\Common\Utilities
       */
     public function getEngineUtilities()
     {
          return $this['engine_common_utilities'];
          
     }
     
      /**
       *  Fetch engine common type repository
       *
       *  @access public
       *  @return \Faker\Components\Engine\Common\TypeRepository
       */
     public function getEngineTypeRepository()
     {
          return $this['engine_common_typerepo'];
     }
     
    /**
      *  Fetch the default event dispatcher
      *
      *  @access public
      *  @return \Symfony\Component\EventDispatcher\EventDispatcher
      */
     public function getEventDispatcher()
     {
          return $this['event_dispatcher'];
     }
   
     /**
       *  Fetch the DBAL Platform Factory
       *
       *  @access public
       *  @return Faker\PlatformFactory
       */
     public function getDBALPlatformFactory()
     {
          return  $this['platform_factory'];
     }
     
     
     /**
      *  Create a new Formatter Factory
      *  
      *  @access public
      *  @return Faker\Components\Engine\Common\Formatter\FormatterFactory
      */
    public function getFormatterFactory(EventDispatcherInterface $event = null)
    {
        $create = $this->raw('formatter_factory');
        return $create($this,$event);
    }
    
     /**
      *  Fetch the Source IO
      *  
      *  @access public
      *  @return \Faker\Io\Io
      */
    public function getSourceIO()
    {
        return $this['source_io'];
    }
   
    /**
     * Fetch the datasource repositry
     * 
     * @access public
     * @return \Faker\Components\Engine\Common\Datasource\DatasourceRepository
     */ 
    public function getDatasourceRepository()
    {
        return $this['engine_common_datasource_repo'];
    }
   
    //  -------------------------------------------------------------------------
    # Symfony Console

    /**
      *  function getConsole
      *
      *  @access public
      *  @return \Faker\Command\Base\Application
      */
    public function getConsole()
    {
        return $this['console'];
    }

    //  -------------------------------------------------------------------------
    # Detect project folder

    /**
      *  static function detect
      *
      *  Will check if a project directory given in path
      *  matches the folder standard folder layout
      *
      *  @param string $path the path to check
      *  @return boolean true if folder internals match expected layout
      */
    public static function detect($path)
    {
        $path = rtrim($path,'/');

        #check for config folder
        if(is_dir($path . DIRECTORY_SEPARATOR . 'config') === false) {
            return false;
        }

        #check for dump folder
        if(is_dir($path . DIRECTORY_SEPARATOR . 'dump') === false) {
            return false;
        }

        #check for template folder
        if(is_dir($path . DIRECTORY_SEPARATOR . 'template') === false) {
            return false;
        }
        
        #check for extension
        if(is_dir($path . DIRECTORY_SEPARATOR . 'extension') === false) {
            return false;
        }
        
         #check for sources
        if(is_dir($path . DIRECTORY_SEPARATOR . 'sources') === false) {
            return false;
        }

        return true;
    }
}
/* End of File */

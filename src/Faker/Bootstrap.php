<?php
namespace Faker;

use Faker\Command\Base\Application,
    Faker\Project,
    Faker\Path,
    Faker\Autoload,
    Symfony\Component\HttpKernel\Debug\ErrorHandler;

class Bootstrap
{
   
   /**
     *  Boot the application
     *
     *  @return Faker\Project;
     */
   public function boot($version)
   {

      //---------------------------------------------------------------
      // Setup Base Paths
      //
      //--------------------------------------------------------------
      
      $COREPATH   =   __DIR__. DIRECTORY_SEPARATOR . '..'   . DIRECTORY_SEPARATOR;
      
      //------------------------------------------------------------------------------
      // Load the Extension Loader
      //
      //------------------------------------------------------------------------------
      
      
      $ext_loader = new  Autoload();
      
      //------------------------------------------------------------------------------
      // Load the DI Component  which is an Instance the /Fakers/Project
      //
      //------------------------------------------------------------------------------
      
      $project = new Project(new Path());
      
      
      //------------------------------------------------------------------------------
      // FAKER Version 
      //
      //------------------------------------------------------------------------------
      
      define('FAKER_VERSION',$version); 
      
      
      //------------------------------------------------------------------------------
      // Setup the project extension directories.
      //
      // If project folder is set by cmd this path below is overriden in Command.php
      //------------------------------------------------------------------------------
      
      $ext_loader->setExtensionNamespace('Faker\\Extension', $project->getPath()->get());
      
      $ext_loader->setFilter(function($ns){
         return  substr($ns,6); # remove 'Faker\' from namespace  
      });
      
      $ext_loader->register();
      
      //------------------------------------------------------------------------------
      // Assign the autoloader to a DI container
      //
      //------------------------------------------------------------------------------
      
      $project['loader']   = $ext_loader;
      
      
      //---------------------------------------------------------------------
      // Assign the datapath
      //
      //--------------------------------------------------------------------
      
      $project['data_path'] = new Path(__DIR__ . DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .'data');    
      
      //------------------------------------------------------------------------------
      // Load the Symfony2 Cli Shell
      //
      //------------------------------------------------------------------------------
      
      $project['console'] = $project->share( function ($c) use ($project)
      {
         $app = new \Faker\Command\Base\Application($project,'Faker',FAKER_VERSION);
         $app->setHelperSet(new \Symfony\Component\Console\Helper\HelperSet());
         return $app;
      });
      
      
      //---------------------------------------------------------------
      // Bootstrap the logs
      //
      //--------------------------------------------------------------
      
      
      $project['logger'] = $project->share(function($project)
      {
         // Create some handlers
          $sysLog = new \Monolog\Handler\TestHandler();
      
          // Create the main logger of the app
          $logger = new \Monolog\Logger('error');
          $logger->pushHandler($sysLog);
      
          #assign the log to the project
          return $logger;
      });
      
      
      //---------------------------------------------------------------
      // Set ErrorHandlers
      //
      //--------------------------------------------------------------
      
      $project['error'] = $project->share(function($project)
      {
          return new \Faker\Exceptions\ExceptionHandler($project->getLogger(),new \Symfony\Component\Console\Output\ConsoleOutput());
      });
      
      #set global error handler 
      ErrorHandler::register(E_NOTICE);
      
      #set global exception handler this catch exceptions before console is run (it will catch them after)
      set_exception_handler(array($project['error'],'exceptionHandler'));
      
      //---------------------------------------------------------------
      // Database Config Setup
      //
      //--------------------------------------------------------------
      
      $project['has_config'] = function($project)
      {
         # test for a dsn
         if(isset($project['dsn_command']) === true) {
            return true;
          }
         
         # test if config provided 
         $config_manager = $project->getConfigManager();
         $config_name = $project->getConfigName();
          
         if($config_manager->getLoader()->exists($config_name) === false) {
           return false;
         }
         
         return true;
      };
      
      
      $project['config_file'] = $project->share(function($project)
      {
         
         $config_manager = $project->getConfigManager();
      
          if($config_manager === null) {
              throw new \RuntimeException('Config Manager not loaded, must be loaded before booting the database');
          }
      
          $entity = new \Faker\Components\Config\Entity();
          
          # is the dsn set
          # e.g mysql://root:vagrant@localhost:3306/sakila?migration_table=migrations_data
          if(isset($project['dsn_command']) === true) {
            $dsn_parser      = new \Faker\Components\Config\DSNParser();
      
            # attempt to parse dsn for detials
            $parsed          = $dsn_parser->parse($project['dsn_command']);
            $db_type         = ($parsed['phptype'] !== 'oci8') ? $parsed['phptype'] = 'pdo_' . $parsed['phptype'] : $parsed['phptype'];
      
            # parse the dsn config data using the DSN driver.
            $project['config_dsn_factory']->create($parsed['phptype'])->merge($entity,$parsed);
               
               
          } else {
      
             # if config name not set that we use the default
             $config_name = $project->getConfigName();
          
              # check if we can load the config given
              if($config_manager->getLoader()->exists($config_name) === false) {
                  throw new \RuntimeException(sprintf('Missing database config at %s ',$config_name));
              }
      
              # load the config file
              $config_manager->getLoader()->load($config_name,$entity);
          }
          
          # store the global config for later access
          return $entity;
      
      });
      
      //---------------------------------------------------------------
      // Setup Database 
      //
      //--------------------------------------------------------------
      
      $project['database'] = $project->share(function($project)
      {
      
         $entity   = $project['config_file'];
         $platform = $project['platform_factory'];
         
         $connectionParams = array(
              'dbname'      => $entity->getSchema(),
              'user'        => $entity->getUser(),
              'password'    => $entity->getPassword(),
              'host'        => $entity->getHost(),
              'driver'      => $entity->getType(),
              'platform'    => $platform->createFromDriver($entity->getType()),
         );
         
         if($entity->getUnixSocket() != false) {
            $connectionParams['unix_socket'] = $entity->getUnixSocket();
         }
         
         if($entity->getCharset() != false) {
            $connectionParams['charset']     = $entity->getCharset();
         }
         
         if($entity->getPath() != false) {
             $connectionParams['path']       = $entity->getPath();
         }
         
         if($entity->getMemory() != false) {
            $connectionParams['memory']     = $entity->getMemory();
         }
         
         $connection        = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, new \Doctrine\DBAL\Configuration());
         $project['console']->getHelperSet()-> set(new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($connection), 'db');
         
         return $connection;
         
      });
      
      $project['platform_factory'] = $project->share(function($project)
      {
         return new \Faker\PlatformFactory();
      });
      
      
      $project['column_factory'] = $project->share(function($project)
      {
         return new \Faker\ColumnTypeFactory();
      });
      
      
      $project['faker_database'] =  $project->share(function($project)
      {
         $platform = $project['platform_factory'];
          
         if(strpos('@PHP-BIN@', '@PHP-BIN') === 0) {
            // stand-alone version is running
            $path = $project->getDataPath()->get() . DIRECTORY_SEPARATOR . 'faker.sqlite';
         }
         else {
            $path = $project->getDataPath()->get() . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'faker.sqlite';
         }
              
         $connectionParams = array(
            'path' => $path,
            'user' => 'faker',
            'password' => '',
            'driver' => 'pdo_sqlite',
            'platform'    => $platform->createFromDriver('pdo_sqlite'),
         );
      
         return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, new \Doctrine\DBAL\Configuration());
         
      });
      
      //---------------------------------------------------------------
      // Setup Config Manager (lazy loaded)
      //
      //---------------------------------------------------------------
      
      $project['config_manager'] = $project->share(function($project)
      {
          # create the io dependency
          $io = new \Faker\Components\Config\Io($project->getPath()->get());
          $event = $project['event_dispatcher'];
      
          # instance the manager, no database needed here
          return new \Faker\Components\Config\Manager($io,$project);
      });
      
      //---------------------------------------------------------------
      //  Config CLI and DSN Driver Factories
      //
      //---------------------------------------------------------------
      
      $project['config_cli_factory'] = $project->share(function($project)
      {
          return new \Faker\Components\Config\Driver\CLIFactory();
      });
      
      
      $project['config_dsn_factory'] = $project->share(function($project)
      {
          return new \Faker\Components\Config\Driver\DsnFactory();
      });
      
      //---------------------------------------------------------------
      // Setup Templating Manager
      //
      //---------------------------------------------------------------
      
      $project['template_manager'] = $project->share(function($project)
      {
          # create the io dependency
      
          $io = new \Faker\Components\Templating\Io($project->getPath()->get());
      
          # instance the manager, no database needed here
          return new \Faker\Components\Templating\Manager($io,$project);
      
      });
      
      //---------------------------------------------------------------
      // Setup Writter Manager 
      //
      //---------------------------------------------------------------
      
      $project['writer_lines_per_file'] = 500;
      $project['writer_cache_max'] = 1000;
      
      $project['writer_manager'] = $project->share(function($project)
      {
          # create the io dependency
          $io = new \Faker\Components\Writer\Io($project->getPath()->get());
      
          # instance the manager, no database needed here
          $manager = new \Faker\Components\Writer\Manager($io,$project);
      
          $manager->setCacheMax($project['writer_cache_max']);
          $manager->setLinesInFile($project['writer_lines_per_file']);
          
         return $manager;
      
      });
      
      //---------------------------------------------------------------
      // Setup Faker Manager 
      //
      //---------------------------------------------------------------
      
      
      $project['faker_manager'] = $project->share(function($project)
      {
          $io = new \Faker\Components\Faker\Io($project->getPath()->get());
         
          return new \Faker\Components\Faker\Manager($io,$project);
         
      });
      
      //---------------------------------------------------------------
      // Event Dispatcher
      //
      //---------------------------------------------------------------
      
      $project['event_dispatcher'] = $project->share(function($project)
      {
         return new \Symfony\Component\EventDispatcher\EventDispatcher();
      });
      
      
      //---------------------------------------------------------------
      // Console Output
      //
      //---------------------------------------------------------------
      $project['console_output'] = $project->share(function($project)
      {
         return new \Symfony\Component\Console\Output\ConsoleOutput();
      });
      
      //---------------------------------------------------------------
      // Source IO (used to load files from source dir in project)
      // 
      //---------------------------------------------------------------
      
      $project['source_io'] = $project->share(function($project)
      {
         $io = new \Faker\Io\Io($project->getPath()->get());
         $io->setProjectFolder('sources'); 
         return $io;
      });
      
      //---------------------------------------------------------------
      // Parser Manager
      //
      //---------------------------------------------------------------
      
      $project['parser'] = function($project)
      {
         return new \Faker\Parser\Parser($project['event_dispatcher']);
      };
      
      $project['parser_options'] = function($project)
      {
         return new \Faker\Parser\ParseOptions($project['event_dispatcher']);
      };
      
      
      //---------------------------------------------------------------
      // Generator 
      //
      //---------------------------------------------------------------
      
      $project['generator_factory_default'] = 'srand';
      
      $project['generator_factory'] = $project->share(function($project)
      {
         return new \PHPStats\Generator\GeneratorFactory();
      });
      
      $project['random_generator'] = $project->share(function($project)
      {
         return $project['generator_factory']->create($project['generator_factory_default']);
      });
      
      //---------------------------------------------------------------
      // SimpleString Factory
      //
      //---------------------------------------------------------------
      
      $project['simplestring_factory'] = $project->share(function($project) {
         
         return \Faker\Text\SimpleString::create('',null);
         
      });
      
      //---------------------------------------------------------------
      // Locale
      //
      //---------------------------------------------------------------
       
      $project['locale_factory'] = $project->share(function($project)
      {
         return new \Faker\Locale\LocaleFactory($project['simplestring_factory']);
      });
      
      return $project;
   }
};

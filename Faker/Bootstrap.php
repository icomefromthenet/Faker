<?php
namespace Faker;

use Faker\Command\Base\Application,
    Faker\Project,
    Faker\Path,
    Faker\Autoload;

//---------------------------------------------------------------
// Setup Global Error Levels
//
//--------------------------------------------------------------

error_reporting(E_ALL);

ini_set('display_errors', 1);


//---------------------------------------------------------------
// Setup Base Paths
//
//--------------------------------------------------------------


define('COREPATH',   __DIR__. DIRECTORY_SEPARATOR . '..'     . DIRECTORY_SEPARATOR);
define('VENDORPATH', __DIR__. DIRECTORY_SEPARATOR . 'Vendor' . DIRECTORY_SEPARATOR);


//------------------------------------------------------------------------------
// Load our Symfony Class Loader.
//
//------------------------------------------------------------------------------

require_once VENDORPATH .'Symfony' . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'ClassLoader' . DIRECTORY_SEPARATOR .'UniversalClassLoader.php';
require_once COREPATH   .'Faker'   . DIRECTORY_SEPARATOR . 'Autoload.php';

$symfony_auto_loader = new Autoload();
$symfony_auto_loader->registernamespaces(
        array(
          'Symfony'   => VENDORPATH,
          'Monolog'   => VENDORPATH,
          'Faker'     => COREPATH,
          'Doctrine'  => VENDORPATH,
          'Zend'      => VENDORPATH,
          
));

$symfony_auto_loader->registerPrefix('Twig_', VENDORPATH .'Symfony' . DIRECTORY_SEPARATOR);
$symfony_auto_loader->register();



//------------------------------------------------------------------------------
// Load the DI Component  which is an Instance the /Fakers/Project
//
//------------------------------------------------------------------------------

$project = new Project(new Path());


//------------------------------------------------------------------------------
// FAKER Version 
//
//------------------------------------------------------------------------------

define('FAKER_VERSION','1.0'); 


//------------------------------------------------------------------------------
// Setup the project extension directories.
//
// If project folder is set by cmd this path below is overriden in Command.php
//------------------------------------------------------------------------------

$symfony_auto_loader->setExtensionNamespace('Faker\\Components\\Extension', $project->getPath()->get());

$symfony_auto_loader->setFilter(function($ns){
   return  substr($ns,17); # remove 'Faker\Components\' from namespace  
});


//------------------------------------------------------------------------------
// Assign the autoloader to a DI container
//
//------------------------------------------------------------------------------

$project['loader'] = $symfony_auto_loader;

//------------------------------------------------------------------------------
// Load the Symfony2 Cli Shell
//
//------------------------------------------------------------------------------

$project['console'] = $project->share( function ($c) use ($project) {
   return new \Faker\Command\Base\Application($project);
});


//---------------------------------------------------------------
// Bootstrap the logs
//
//--------------------------------------------------------------


$project['logger'] = $project->share(function($project){
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

$project['error'] = $project->share(function($project){
    return new \Faker\Exceptions\ExceptionHandler($project->getLogger(),new \Symfony\Component\Console\Output\ConsoleOutput());
});

#set global error handlers
set_error_handler(array($project['error'],'errorHandler'));

#set global exception handler
set_exception_handler(array($project['error'],'exceptionHandler'));

$project['has_config'] = function($project) {
   
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


$project['config_file'] = $project->share(function($project){
   
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
// Setup Database (lazy loaded)
//
//--------------------------------------------------------------

$project['database'] = $project->share(function($project){

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
   
   return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, new \Doctrine\DBAL\Configuration());
   
});

$project['platform_factory'] = $project->share(function($project){
   return new \Faker\PlatformFactory();
});


$project['column_factory'] = $project->share(function($project){
   return new \Faker\ColumnTypeFactory();
});


$project['faker_database'] =  $project->share(function($project){

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

$project['config_manager'] = $project->share(function($project){
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

$project['config_cli_factory'] = $project->share(function($project){

    return new \Faker\Components\Config\Driver\CLIFactory();
});


$project['config_dsn_factory'] = $project->share(function($project){

    return new \Faker\Components\Config\Driver\DsnFactory();
});

//---------------------------------------------------------------
// Setup Templating Manager (lazy loaded)
//
//---------------------------------------------------------------

$project['template_manager'] = $project->share(function($project){
    # create the io dependency

    $io = new \Faker\Components\Templating\Io($project->getPath()->get());

    # instance the manager, no database needed here
    return new \Faker\Components\Templating\Manager($io,$project);

});

//---------------------------------------------------------------
// Setup Writter Manager (lazy loaded)
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
// Setup Writter Manager (lazy loaded)
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

$project['event_dispatcher'] = $project->share(function($project){
   
   return new \Symfony\Component\EventDispatcher\EventDispatcher();
});


//---------------------------------------------------------------
// Console Output
//
//---------------------------------------------------------------
$project['console_output'] = $project->share(function($project){
   
   return new \Symfony\Component\Console\Output\ConsoleOutput();
});

//---------------------------------------------------------------
// Source IO (used to load files from source dir in project)
// 
//---------------------------------------------------------------

$project['source_io'] = $project->share(function($project){
   $io = new \Faker\Io\Io($project->getPath()->get());
   $io->setProjectFolder('sources'); 
   return $io;
});

//---------------------------------------------------------------
// Parser Manager
//
//---------------------------------------------------------------

$project['parser'] = function($project){
   return new \Faker\Parser\Parser($project['event_dispatcher']);
};

$project['parser_options'] = function($project){
   return new \Faker\Parser\ParseOptions($project['event_dispatcher']);
};

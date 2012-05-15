<?php
namespace Faker;

use Faker\Command\Base\Application;
use Faker\Project;
use Faker\Path;
use Faker\Bootstrap\Log as BootLog;
use Faker\Bootstrap\Error as BootError;
use Faker\Bootstrap\Database as BootDatabase;
use Faker\Autoload;

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


define('COREPATH',   __DIR__.'/..'     .DIRECTORY_SEPARATOR);
define('VENDORPATH', __DIR__.'/Vendor' .DIRECTORY_SEPARATOR);


//------------------------------------------------------------------------------
// Load our Symfony Class Loader.
//
//------------------------------------------------------------------------------

require_once VENDORPATH .'Symfony' .DIRECTORY_SEPARATOR. 'Component' .DIRECTORY_SEPARATOR. 'ClassLoader' .DIRECTORY_SEPARATOR.'UniversalClassLoader.php';
require_once COREPATH .'Faker' .DIRECTORY_SEPARATOR. 'Autoload.php';

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

//---------------------------------------------------------------
// Setup Database (lazy loaded)
//
//--------------------------------------------------------------

$project['config_name'] = 'config';
$project['database'] = $project->share(function($project){

    $config_manager = $project->getConfigManager();

    if($config_manager === null) {
        throw new \RuntimeException('Config Manager not loaded, must be loaded before booting the database');
    }

    # if config name not set that we use the default
    $config_name = ($project->getConfigName() === null) ?  'config.php' : $project->getConfigName(). '.php';

        # is the dsn set
    if(isset($project['dsn_command']) === true) {

        $dsn =  $project['dsn_command'];
        $user = $project['username_command'];
        $password = $project['password_command'];

        $connectionParams = array('pdo' => new \PDO($dsn,$user,$password));

    } else {

        # check if we can load the config given
        if($config_manager->getLoader()->exists($config_name) === false) {
           throw new \RuntimeException(sprintf('Missing database config at %s ',$config_name));
        }

        # load the config file
        $entity = $config_manager->getLoader()->load($config_name,new Faker\Components\Config\Entity());

        $connectionParams = array(
        'dbname' => $entity->getSchema(),
        'user' => $entity->getUser(),
        'password' => $entity->getPassword(),
        'host' => $entity->getHost(),
        'driver' => $entity->getType(),
        );

    }

    return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, new \Doctrine\DBAL\Configuration());
});

$project['faker_database'] =  $project->share(function($project){

   
        $connectionParams = array(
        'path' => $project->getDataPath()->get() . DIRECTORY_SEPARATOR . 'faker.sqlite',
        'user' => 'faker',
        'password' => '',
        'driver' => 'pdo_sqlite',
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
// Setup Faker Manager (lazy loaded)
//
//---------------------------------------------------------------

$project['schema_name'] = 'default';

$project['Faker_manager'] = $project->share(function($project){
    $io = new \Faker\Components\Faker\Io($project->getPath()->get());
    $io->setProjectFolder('Faker'. DIRECTORY_SEPARATOR . $project['schema_name']);
  
    # instance the manager, no database needed here
    return new \Faker\Components\Faker\Manager($io,$project);
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
    $io = new \Faker\Components\Faker\Io($project->getPath()->get());    $event = $project['event_dispatcher'];
   
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

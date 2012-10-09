#!/usr/bin/env php
<?php
namespace Faker;

use Faker\Bootstrap,
    Faker\Project,
    Faker\Path,
    Faker\Command\Application,
    Faker\Command\GenerateCommand,
    Faker\Command\AnalyseCommand,
    Faker\Command\ConfigureCommand,
    Faker\Command\InitProjectCommand,
    Faker\Command\Doctrine\ImportCommand,
    Faker\Command\Doctrine\ReservedWordsCommand,
    Faker\Command\Doctrine\RunSqlCommand,
    Symfony\Component\Console\Helper\HelperSet,
    Symfony\Component\Console\Helper\DialogHelper;
    

//---------------------------------------------------------------
// Setup Global Error Levels
//
//--------------------------------------------------------------
   
error_reporting(E_ALL);
   
ini_set('display_errors', 1);
       

//---------------------------------------------------------------
// Load Composer
//
//--------------------------------------------------------------

if (is_dir($vendor = __DIR__.'/../vendor')) {
   require($vendor.'/autoload.php');
} elseif (is_dir($vendor = __DIR__.'/../../../../vendor')) {
   require($vendor.'/autoload.php');
} 
else {
    die(
        'You must set up the project dependencies, run the following commands:'.PHP_EOL.
        'curl -s http://getcomposer.org/installer | php'.PHP_EOL.
        'php composer.phar install'.PHP_EOL
    );
}


$boot = new Bootstrap();

$project = $boot->boot('1.0.3');


//---------------------------------------------------------------------
// Create Helper sets for commands
//
//--------------------------------------------------------------------

$project->getConsole()->setHelperSet(new HelperSet(array('dialog' => new DialogHelper())));


//---------------------------------------------------------------------
// Inject Faker Install Ccommands
//
//--------------------------------------------------------------------

$project->getConsole()->add(new GenerateCommand('faker:generate'));
$project->getConsole()->add(new AnalyseCommand('faker:analyse'));
$project->getConsole()->add(new ConfigureCommand('faker:configure'));
$project->getConsole()->add(new InitProjectCommand('faker:init'));

//---------------------------------------------------------------------
// Inject Doctine Commands
//
//--------------------------------------------------------------------


$project->getConsole()->add(new ImportCommand());
$project->getConsole()->add(new ReservedWordsCommand());
$project->getConsole()->add(new RunSqlCommand());



//--------------------------------------------------------------------
// Run the App
//--------------------------------------------------------------------

$project->getConsole()->run();


/* End of Class */

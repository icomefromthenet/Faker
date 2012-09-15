#!/usr/bin/env php
<?php
namespace Faker;

use Faker\Project,
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
       
  
   
//---------------------------------------------------------------------
// Set Pear Directories
//
//--------------------------------------------------------------------
    
    
if(strpos('@PHP-BIN@', '@PHP-BIN') === 0) { // stand-alone version is running

   set_include_path(dirname(__FILE__) . '/../' . PATH_SEPARATOR . get_include_path());
   $project = require 'Faker' . DIRECTORY_SEPARATOR .'Bootstrap.php';
   $project['data_path'] = new Path(__DIR__ . DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR .'data');      

}else {
   $project = require 'Faker' . DIRECTORY_SEPARATOR .'Bootstrap.php';
   $project['data_path'] = new Path('@PEAR-DATA@' . DIRECTORY_SEPARATOR .'Faker');   
}


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

#!/usr/bin/env php
<?php
namespace Faker;

use Faker\Project,
    Faker\Path,
    Faker\Command\Application,
    Faker\Command\GenerateCommand,
    Faker\Command\AnalyseCommand,
    Faker\Command\ConfigureCommand,
    Faker\Command\InitProjectCommand;

//---------------------------------------------------------------------
// Set Pear Directories
//
//--------------------------------------------------------------------
    
    
if(strpos('@PHP-BIN@', '@PHP-BIN') === 0) { // stand-alone version is running

   set_include_path(dirname(__FILE__) . '/../' . PATH_SEPARATOR . get_include_path());
   require 'Faker' . DIRECTORY_SEPARATOR .'Bootstrap.php';
   $project['data_path'] = new Path(__DIR__ . DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR .'data');      

}else {
   require 'Faker' . DIRECTORY_SEPARATOR .'Bootstrap.php';
   $project['data_path'] = new Path('@PEAR-DATA@' . DIRECTORY_SEPARATOR .'Faker');   
}



//---------------------------------------------------------------------
// Inject Faker Install Ccommands
//
//--------------------------------------------------------------------

$project->getConsole()->add(new GenerateCommand('faker:generate'));
$project->getConsole()->add(new AnalyseCommand('faker:analyse'));
$project->getConsole()->add(new ConfigureCommand('faker:configure'));
$project->getConsole()->add(new InitProjectCommand('faker:init'));


//--------------------------------------------------------------------
// Run the App
//--------------------------------------------------------------------

$project->getConsole()->run();


/* End of Class */

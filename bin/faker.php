#!/opt/lampp/bin/php
<?php
use Faker\Project;
use Faker\Command\Application;
use Faker\Command\GenerateCommand;
use Faker\Command\AnalyseCommand;
use Faker\Command\ConfigureCommand


if(strpos('@PHP-BIN@', '@PHP-BIN@') === 0) {
    //not a pear install run normally

  require __DIR__ .'/../src/Faker/Bootstrap.php';

}
else {
   require '@PEAR-DIR@/Faker/Bootstrap.php';
}

//---------------------------------------------------------------------
// Set Pear Directories
//
//--------------------------------------------------------------------

if(strpos('@PHP-BIN@', '@PHP-BIN@') === 0) {
    //not a pear install run normally
  $project['data_path'] = new \Faker\Path(__DIR__ .'/../data');
}
else {
   $project['data_path'] = new \Faker\Path('@PEAR-DATA@');
}

//---------------------------------------------------------------------
// Inject Faker Install Ccommands
//
//--------------------------------------------------------------------

$project->getConsole()->add(new GenerateCommand('generate'));
$project->getConsole()->add(new AnalyseCommand('analyse'));
$project->getConsole()->add(new AnalyseCommand('configure'));


//--------------------------------------------------------------------
// Run the App
//--------------------------------------------------------------------

$project->getConsole()->run();


/* End of Class */

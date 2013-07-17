#!/usr/bin/env php
<?php
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Zend\ProgressBar\ProgressBar;
use Zend\ProgressBar\Adapter\Console as ZendConsoleAdapter;
use Faker\Components\Engine\Common\Composite\TableNode;
use Faker\Components\Engine\Common\Output\ProgressBarOutputter;
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

$autoloader = require __DIR__.'/../vendor/autoload.php';

//---------------------------------------------------------------
// Boot Faker
//
//--------------------------------------------------------------

$boot        = new Faker\Bootstrap();
$application = $boot->boot('1.0.4',$autoloader);

# point fake at skelton project folder as don't have actual project folder
$application->getPath()->parse(__DIR__ . '/../skelton');
       
# change the extension directories to skelton folder
$application['loader']->setExtensionNamespace(
               'Faker\\Extension' , $application->getPath()->get()
);

$p = __DIR__;

# need to change the output path the writter uses
$application['writer_manager'] = $application->share($application->extend('writer_manager',function($manager,$c) use ($p) {
   $manager->getIo()->setBase($p);
   $manager->getIo()->setProjectFolder('dump');
   return $manager; 
}));

//---------------------------------------------------------------
// Setup Console
//
//--------------------------------------------------------------

$console = $application->getConsole();

//---------------------------------------------------------------
// Add Console Commands
//
//--------------------------------------------------------------

$console->register('faker:run')
        ->setDescription('Run a faker file')
        ->addArgument('file',InputArgument::REQUIRED,'name file under this dir to run',null)
        ->setCode(function (InputInterface $input, OutputInterface $output) use ($application) {

        $fileName = __DIR__ . '/'.$input->getArgument('file');
        $event    = $application->getEventDispatcher();
        
        if(is_file($fileName) === false) {
            throw new \RuntimeException('File does not exist');
        }
        
        $splFileInfo = new SplFileInfo($fileName);
        
        #Setup console ouput to list to build events 
        $event->addSubscriber(new Faker\Components\Engine\Common\Output\BuilderConsoleOutput($event,$output));
        
        $output->writeln('<info>Starting Generator</info>');
        
        # command only supports php builder and Entity Builder.
        $composite = include($splFileInfo->getRealPath());
    
        
        # if none returned from php file must be an entity generator        
        if($composite !==  null) {
            
             # use the composite to calculate number of rows
            $rows = 0;
                
            foreach($composite->getChildren() as $table) {
                if($table instanceof TableNode) {
                  $rows +=  $table->getRowsToGenerate();
                }
                
            }
            
            # instance zend_progress bar
            $console_adapter = new ZendConsoleAdapter();
            $console_adapter->setElements(array(ZendConsoleAdapter::ELEMENT_PERCENT,
                                ZendConsoleAdapter::ELEMENT_BAR,
                                ZendConsoleAdapter::ELEMENT_TEXT,
                            ));
                
            $progress_bar = new ProgressBar($console_adapter, 1, $rows,null);
               
            # instance the default notifier
            $event->addSubscriber(new ProgressBarOutputter($event,$progress_bar));
            
            # start execution of the generate
            $result = array();
            $composite->generate(1,$result);    
        }
    
    $output->writeln(sprintf("%s <info>success</info>", 'faker:run'));
});

//---------------------------------------------------------------
// Run the console
//
//--------------------------------------------------------------
$console->run();
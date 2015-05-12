#!/usr/bin/env php
<?php
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Zend\ProgressBar\ProgressBar;
use Zend\ProgressBar\Adapter\Console as ZendConsoleAdapter;
use Faker\Components\Engine\Common\Composite\TableNode;
use Faker\Components\Engine\Common\Output\ProgressBarOutputter;
use Faker\Components\Engine\Common\Output\DebugOutputter;
use Faker\Project;

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
        
        
        $output->writeln('<info>Starting Generator</info>');
       
    
        #Setup console ouput to list to build events 
        $event->addSubscriber(new Faker\Components\Engine\Common\Output\BuilderConsoleOutput($event,$output));
        
        # if none returned from php file must be an entity generator        
        
         if(pathinfo($splFileInfo->getFilename(), PATHINFO_EXTENSION) == 'xml') {
            # parse the schema file
            $builder = $parser->parse(FileFactory::create($splFileInfo->getPathname()), new ParseOptions()); 
        
            # fetch the composite
            $composite = $builder->build();
        } else {
            # try load a php file
            $composite = include($splFileInfo->getPathname());
        }
        
        # check if we use the debug or normal notifier
        if($input->getOption('verbose')) {
            
            $event->addSubscriber(new DebugOutputter($output));
        
            
        } elseif ($composite instanceof SchemaNode) {
        
           
                
        } elseif ($composite instanceof Project) {
            
          
           $rows = $composite->howManyRows();
            
            # instance zend_progress bar
            $console_adapter = new ZendConsoleAdapter();
            $console_adapter->setElements(array(ZendConsoleAdapter::ELEMENT_PERCENT,
                                ZendConsoleAdapter::ELEMENT_BAR,
                                ZendConsoleAdapter::ELEMENT_TEXT,
                            ));
           $progress_bar = new ProgressBar($console_adapter, 1, $rows,null);
           
            # instance the default notifier
            $event->addSubscriber(new ProgressBarOutputter($event,$progress_bar));
        }
        else {
            throw new RuntimeException('Unknown return from project file');
        }
        
        # start execution of the generate
        if($composite instanceof GeneratorInterface) {
            $result = array();
            $composite->generate(1,$result,array());
            
        } elseif($composite instanceof Project){
            $composite->generate(); 
        }
        else {
            throw new \RuntimeException('No Composite with GeneratorInterface found');
        }
        
        $output->writeln(sprintf("%s <info>success</info>", 'faker:run'));
});

//---------------------------------------------------------------
// Run the console
//
//--------------------------------------------------------------
$console->run();
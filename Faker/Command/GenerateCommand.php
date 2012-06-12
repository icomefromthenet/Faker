<?php
namespace Faker\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Helper\DialogHelper,
    Symfony\Component\Console\Output\OutputInterface,
    Faker\Command\Base\Command,
    Faker\Components\Faker\DebugOutputter,
    Faker\Components\Faker\ProgressBarOutputter,
    Faker\Parser\FileFactory,
    Faker\Parser\ParseOptions,
    Faker\Command\Base\FakerCommand,
    Zend\ProgressBar\ProgressBar,
    Zend\ProgressBar\Adapter\Console as ZendConsoleAdapter;

class GenerateCommand extends Command
{
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        # get the di container 
        $project  = $this->getApplication()->getProject();
           
        # load the faker component manager
        $faker_manager  = $project['faker_manager'];
       
        #event manager
        $event = $project['event_dispatcher'];
       
         # fetch the schem parser        
        $parser = $faker_manager->getSchemaParser();
        $parser->register();
        
        # fetch the file and verify the path
        $schema_file =  $input->getArgument('schema'); 
        
        $source_io = $project['source_io'];
        if($source_io->exists($schema_file) === false) {
            throw new \RuntimeException("File $schema_file not found under /source");   
        }
        
        $file =  $source_io->load($schema_file,'',true);          
        
        # parse the schema file
        $builder = $parser->parse(FileFactory::create($file->getPathname()), new ParseOptions()); 
        
        # fetch the composite
        $composite = $builder->build();
        
        # check if we use the debug or normal notifier
        if($input->getOption('verbose')) {
            
            $event->addSubscriber(new DebugOutputter($output));
                
        } else {
                 # use the composite to calculate number of rows
            $rows = 0;
            
            foreach($composite->getChildren() as $table) {
                $rows +=  $table->getToGenerate();                      
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
    
        }

        # start execution of the generate
        $composite->generate(1,array());
    }


    protected function configure()
    {
        $this->setDescription('Will generate the faker data');
        $this->setHelp(<<<EOF
Parse the given schema and generate data.

Example

faker:generate schema.xml      <info>Parse schema.xml in sources dir.</info>

faker:generate schema.xml true <info>Use the debug outputter.</info>

faker:generate                 <info>Parse schema.xml (default) in sources dir.</info>

EOF
        );

        $this->addArgument('schema',InputArgument::OPTIONAL, 'The name of the schema file','schema.xml');
        
        parent::configure();
    }

}
/* End of File */
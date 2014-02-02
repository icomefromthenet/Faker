<?php
namespace Faker\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Faker\Command\Base\Command;
use Faker\Components\Engine\Common\ResultPrinterInterface;
use Faker\Components\Engine\Common\Output\DebugOutputter;
use Faker\Components\Engine\Common\Output\ProgressBarOutputter;
use Faker\Components\Engine\Common\Output\BuilderConsoleOutput;
use Faker\Components\Engine\Common\Composite\GeneratorInterface;
use Faker\Components\Engine\DB\Composite\SchemaNode;
use Faker\Parser\FileFactory;
use Faker\Parser\ParseOptions;
use Faker\Command\Base\FakerCommand;
use Zend\ProgressBar\ProgressBar;
use Zend\ProgressBar\Adapter\Console as ZendConsoleAdapter;
use Faker\Components\Engine\Common\Composite\TableNode;

class GenerateCommand extends Command
{
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        # get the di container 
        $project  = $this->getApplication()->getProject();
           
        #event manager
        $event = $project['event_dispatcher'];
       
        # fetch the schem parser        
        $parser = $project->getXMLEngineParser();
        $parser->register();
        
        # fetch the file and verify the path
        $schema_file =  $input->getArgument('schema'); 
        
        $source_io = $project['source_io'];
        if($source_io->exists($schema_file) === false) {
            throw new \RuntimeException("File $schema_file not found under /source");   
        }
        
        $file =  $source_io->load($schema_file,'',true);          
        
        # bind build events output handler
        $event->addSubscriber(new BuilderConsoleOutput($event,$output));
        
        $output->writeln('<info>Starting Generator</info>');
        
        
        # is file php or xml
        if(pathinfo($file->getFilename(), PATHINFO_EXTENSION) == 'xml') {
            # parse the schema file
            $builder = $parser->parse(FileFactory::create($file->getPathname()), new ParseOptions()); 
        
            # fetch the composite
            $composite = $builder->build();
        } else {
            # try load a php file
            $composite = include($file->getPathname());
        }
        
        # check if we use the debug or normal notifier
        if($input->getOption('verbose')) {
            $event->addSubscriber(new DebugOutputter($output));
        } elseif ($composite instanceof SchemaNode) {
        
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
                
        }
        
        # start execution of the generate
        $result = array();
        
        if($composite instanceof GeneratorInterface) {
            $composite->generate(1,$result,array());
        }
        else {
            throw new \RuntimeException('No Composite with GeneratorInterface found');
        }
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

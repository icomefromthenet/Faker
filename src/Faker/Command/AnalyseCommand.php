<?php
namespace Faker\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Helper\DialogHelper,
    Symfony\Component\Console\Output\OutputInterface,
    Faker\Io\FileExistException,
    Faker\Command\Base\Command;

class AnalyseCommand extends Command
{


    public function execute(InputInterface $input, OutputInterface $output)
    {
           # get the di container 
           $project  = $this->getApplication()->getProject();
           
           # throw an exception if name is invalid
           $databasePool = $project->getDatabase()->getFakerConnectionPool();
           $database     = $databasePool->getExtraConnection($input->getArgument('connname'));
           
           
           # verify if a output file name been passed
           if(($out_file_name = $input->getArgument('out') ) === null) {
              $out_file_name = 'schema.xml';
           } else {
              $out_file_name = rtrim($out_file_name,'.xml').'.xml';
           }
           
           # load the schema analyser
           $schema_analyser = $project->getSchemaAnalyser();
           
           #run the analyser
           $schema = $schema_analyser->analyse($database,$project->getXMLEngineBuilder());
    
           # write the scheam file to the project folder (sources)
           $sources_io = $project->getSourceIO();
           
           $formatted_xml = $schema_analyser->format($schema->toXml());
           
           
         try {

            #Write config file to the project
           $sources_io->write($out_file_name,'',$formatted_xml,$overrite = false);
           $output->writeLn('<comment>++</comment> <info>sources/'. $out_file_name .'</info>');
           

         }
         catch(FileExistException $e) {
            #ask if they want to overrite
           $dialog = new DialogHelper();
           $answer = $dialog->askConfirmation($output,"<question>$out_file_name already exists do you want to Overrite? [y|n]</question>:",false);

            if($answer) {
                #Write config file to the project
                  $sources_io->write($out_file_name,'',$formatted_xml,$overrite = true);
                  $output->writeLn('<comment>++</comment> <info>sources/'. $out_file_name .'</info>');
           
            }

         }
    }


     protected function configure() {

        $this->setDescription('Analyse the configured database and create a new faker schema');
        $this->setHelp(<<<EOF
Will <info>create a new faker schema</info> using the configured database.

A database must be configured first using <info>config</info> command.
you can specify the name of the output file as show below.

Example:

<comment>Will create schema called myschema.xml using connection with name connA</comment>
>> faker:analyse connA myschema

<comment>Will default schema to schema.xml with connection of connB</comment>
>> faker:analyse connB

EOF
    );
        $this->addArgument('connname',
                             InputArgument::REQUIRED,
                            'The name of the connection to use'
                            );
          
        $this->addArgument('out',
                             InputArgument::OPTIONAL,
                            'file name of the faker schema to generate'
                            );
        
        
        parent::configure();
    }

}
/* End of File */
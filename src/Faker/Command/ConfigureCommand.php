<?php
namespace Faker\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Helper\DialogHelper,
    Faker\Command\Base\Command,
    Faker\Components\Config\Io as ConfigIo,
    Faker\Components\Config\Manager,
    Faker\Io\FileExistException,
    Faker\Components\Config\Entity;


class ConfigureCommand extends Command
{


    protected $answers;

    
    /**
     * Interacts with the user.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = new DialogHelper();
        $answers =  array();

        # Ask for the database type
        $answers['type'] =  strtolower($dialog->ask($output,'<question>Which Database does this belong? [mysql|mssql|oracle|posgsql|oci8|sqlite]: </question>','mysql'));

        # apply format of the Doctrine DBAL
        $answers['type'] = ($answers['type'] !== 'oci8') ? $answers['type'] = 'pdo_' . $answers['type'] : $answers['type'];        
        
        # fetch interact from the Config/Driver/CLI
        $questions_driver = $this->getApplication()->getProject()->getConfigManager()->getCLIFactory()->create($answers['type']);

        $answers = $questions_driver->interact($dialog,$output,$answers);
        
        # Store answers for the execute method
        $this->answers = $answers;

      
        return true;
    }

    //  -------------------------------------------------------------------------
    # Execute

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $project = $this->getApplication()->getProject();
        $manager = $project['config_manager'];

        try {

            # get the CLI Config Driver
            $driver = $project->getConfigManager()->getCLIFactory()->create($this->answers['type']);
            $entity = $driver->merge(new Entity(),$this->answers);
        
            #Write config file to the project
            $manager->getWriter()->write($entity,$project->getConfigName());

        }
        catch(FileExistException $e) {
            #ask if they want to overrite
           $dialog = new DialogHelper();
           $answer = $dialog->askConfirmation($output,'Config <info>Exists</info> do you want to <info>Overrite?</info> [y|n] :',false);

            if($answer) {
                #Write config file to the project
                $manager->getWriter()->write($entity,$project->getConfigName(),true);

            }
        }

        # reload the config file (needed in shell mode)
        $project['config_file'];
        
        # tell them the file was written
        $output->writeln(sprintf("++ Writing <comment>config file</comment>  %s",$project->getConfigName()));

    }


    protected function configure()
    {
        $this->setDescription('Will create / overrite the configuration');
        $this->setHelp(<<<EOF
Write a <info>new configuration file</info> to the project folder:

Example

>> faker:configure

Will as you the following questions which can include: 

Type of Database [mysql|oracle|mssql|pgsql|sqlite|oci8]?
Database Schema Name?
Database user Password?
Database user Name?
EOF
);

        parent::configure();
    }

}
/* End of File */

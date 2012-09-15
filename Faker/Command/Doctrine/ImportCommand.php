<?php
namespace Faker\Command\Doctrine;

use Doctrine\DBAL\Tools\Console\Command\ImportCommand as DoctrineImportCommand,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console,
    Faker\Project,
    Faker\Exception as FakerException;

/**
 * Task for executing arbitrary SQL that can come from a file or directly from
 * the command line.
 *
 * 
 */
class ImportCommand extends DoctrineImportCommand
{
    /**
     * @see Console\Command\Command
     */
    protected function configure()
    {
        parent::configure();
        
        $this->addOption('--path','-p', InputOption::VALUE_OPTIONAL,'the project folder path',false);
        
        # mysql://root:vagrant@tcp(localhost:3306)/sakila
        # http://pear.php.net/manual/en/package.database.db.intro-dsn.php
        $this->addOption('--dsn', '',   InputOption::VALUE_OPTIONAL,'DSN to connect to db',false);
        
    }
    
    /**
     * @see Console\Command\Command
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
       $project = $this->getApplication()->getProject();

       if (true === $input->hasParameterOption(array('--path', '-p'))) {
            #switch path to the argument
            $project->getPath()->parse((string)$input->getOption('path'));
       
            # change the extension directories
            $project['loader']->setExtensionNamespace(
               'Faker\\Extension' , $project->getPath()->get()
            );
       }

        #try and detect if path exits
        $path = $project->getPath()->get();
        if($path === false) {
            throw new \RuntimeException('Project Folder does not exist');
        }

        # path exists does it have a project
        if(Project::detect((string)$path) === false && $this->getName() !== 'faker:init') {
            throw new \RuntimeException('Project Folder does not contain the correct folder heirarchy');
        }
        
        # load the extension bootstrap the path has been verifed to contain an extension folder
        if($this->getName() !== 'faker:init') {
          $project->getPath()->loadExtensionBootstrap();    
        }


        if (true === $input->hasParameterOption(array('--schema'))) {
            #switch path to the argument
            $project['schema_name'] = $input->getOption('schema');;
        }

        # Test for DSN
        if (true === $input->hasParameterOption(array('--dsn'))) {
            $project['dsn_command'] = $input->getOption('dsn');
        }
        
        
        parent::initialize($input,$output);
        
    }
    
    /**
     * @see Console\Command\Command
     */
    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
            $this->getApplication()->getProject()->getDatabase();
            parent::execute($input,$output);
    }
    
}

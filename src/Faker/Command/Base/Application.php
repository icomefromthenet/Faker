<?php
namespace Faker\Command\Base;

use Symfony\Component\Console\Application as BaseApplication,
    Symfony\Component\Console\Shell,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\ArgvInput,
    Symfony\Component\Console\Input\ArrayInput,
    Symfony\Component\Console\Input\InputDefinition,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Output\Output,
    Symfony\Component\Console\Output\ConsoleOutput,
    Symfony\Component\Console\Output\ConsoleOutputInterface,
    Symfony\Component\Console\Command\HelpCommand,
    Symfony\Component\Console\Command\ListCommand,
    Symfony\Component\Console\Helper\HelperSet,
    Symfony\Component\Console\Helper\FormatterHelper,
    Symfony\Component\Console\Helper\DialogHelper,
    Faker\Project;

class Application extends BaseApplication
{

    protected $project;

    /**
      *  function setProject
      *
      *  @param \Faker\Project $project
      *  @access public
      */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
      *  function getProject
      *
      *  @access public
      *  @return Faker\Project
      */
    public function getProject()
    {
        return $this->project;
    }


    //  -------------------------------------------------------------------------

    /*
     * __construct()
     *
     * @param \Faker\Project $project
     *
     */
    public function __construct(Project $project,$name = 'Faker',$version)
    {   
        parent::__construct($name,$version);

        $this->getDefinition()->addOptions(array(
                new InputOption('--shell', '-s',   InputOption::VALUE_NONE, 'Launch the shell.'),
        ));

        #set the references
        $this->setProject($project);
    }

    
    //  --------------------------------------------------------------------------
     /**
     * Runs the current application.
     *
     * @param InputInterface  $input  An Input instance
     * @param OutputInterface $output An Output instance
     *
     * @return integer 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        
        $output->writeLn($this->getHeader());
        $name = $this->getCommandName($input);
        
        if (true === $input->hasParameterOption(array('--shell', '-s'))) {
            $shell = new Shell($this);
            $shell->run();
            return 0;
        }

        if (true === $input->hasParameterOption(array('--ansi'))) {
            $output->setDecorated(true);
        } elseif (true === $input->hasParameterOption(array('--no-ansi'))) {
            $output->setDecorated(false);
        }

        if (true === $input->hasParameterOption(array('--help', '-h'))) {
            if (!$name) {
                $name = 'help';
                $input = new ArrayInput(array('command' => 'help'));
            } else {
                $this->wantHelps = true;
            }
        }

        if (true === $input->hasParameterOption(array('--no-interaction', '-n'))) {
            $input->setInteractive(false);
        }

        if (function_exists('posix_isatty') && $this->getHelperSet()->has('dialog')) {
            $inputStream = $this->getHelperSet()->get('dialog')->getInputStream();
            if (!posix_isatty($inputStream)) {
                $input->setInteractive(false);
            }
        }

        if (true === $input->hasParameterOption(array('--quiet', '-q'))) {
            $output->setVerbosity(OutputInterface::VERBOSITY_QUIET);
        } elseif (true === $input->hasParameterOption(array('--verbose', '-v'))) {
            $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
        }

        if (true === $input->hasParameterOption(array('--version', '-V'))) {
            $output->writeln($this->getLongVersion());

            return 0;
        }

        if (!$name) {
            $name = 'list';
            $input = new ArrayInput(array('command' => 'list'));
        }

        // the command name MUST be the first element of the input
        $command = $this->find($name);

        $this->runningCommand = $command;
        $statusCode = $command->run($input, $output);
        $this->runningCommand = null;
        
        # write Footer
        $output->writeLn($this->getFooter());
        
        return is_numeric($statusCode) ? $statusCode : 0;
    
    }
    
    
    /**
     * Returns the Basic header.
     *
     * @return string the header string
     */
    public function getHeader()
    {
       return <<<EOF
         _______    ___       __  ___  _______ .______           _______  _______ .__   __. 
        |   ____|  /   \     |  |/  / |   ____||   _  \         /  _____||   ____||  \ |  | 
        |  |__    /  ^  \    |  '  /  |  |__   |  |_)  |       |  |  __  |  |__   |   \|  | 
        |   __|  /  /_\  \   |    <   |   __|  |      /        |  | |_ | |   __|  |  . `  | 
        |  |    /  _____  \  |  .  \  |  |____ |  |\  \----.   |  |__| | |  |____ |  |\   | 
        |__|   /__/     \__\ |__|\__\ |_______|| _| `._____|    \______| |_______||__| \__|

EOF;

}



  /**
     * Returns the Basic footer.
     *
     * @return string the footer string
     */
      
  public function getFooter()
    {
        return <<<EOF

<info>
Finished.  
</info>


EOF;

}



}
/* End of File */

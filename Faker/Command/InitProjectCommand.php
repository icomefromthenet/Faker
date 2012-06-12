<?php
namespace Faker\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Faker\Io\Io as BaseIo,
    Faker\Command\Base\Command;

class InitProjectCommand extends Command
{


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if(strpos('@PHP-BIN@','@PHP-BIN') === 0) {
            $skelton = realpath(__DIR__ .'/../../skelton');
        }
        else {
            $skelton = '@PEAR-DATA@'. DIRECTORY_SEPARATOR .'Faker'. DIRECTORY_SEPARATOR .'skelton';
        }

        $project_folder = new BaseIo($this->getApplication()->getProject()->getPath()->get());
        $skelton_folder = new BaseIo($skelton);
        
        

        # ask for confirmation if dir is not empty
        if($project_folder->isEmpty() === false) {
            # as for confirmation
            $dialog = $this->getHelperSet()->get('dialog');
            if (!$dialog->askConfirmation($output, '<question>Folder is not empty continue? [y|n]</question>', false)) {
                return;
            }
        }
        
        $this->getApplication()->getProject()->build($project_folder,$skelton_folder,$output);
       
    }


    protected function configure()
    {

        $this->setDescription('Will write a project folder to location');
        $this->setHelp(<<<EOF
Write a <info>new project folder</info> to the destination:

This is the first command you should run, the folder must exist, you will
normally run this within the project folder but can be overriden with -p option.

Example
<comment>Override the path</comment>
>> faker:init <info> -p /home/bob/project </info>

<comment>Build into the current folder.</comment>
>> faker:init

EOF
                );
        $this->setDefinition(array(
            new InputArgument(
                    'folder',
                    InputArgument::OPTIONAL,
                    'Folder to place the project into',
                    ''
                    )
        ));

        parent::configure();
    }


}
/* End of File */

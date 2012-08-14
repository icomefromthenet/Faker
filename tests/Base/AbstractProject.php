<?php
namespace Faker\Tests\Base;

use Faker\Project,
    Faker\Io\Io,
    Faker\Path,
    Faker\Components\Faker\Collection,
    Symfony\Component\Console\Output\NullOutput,
    Symfony\Component\EventDispatcher\EventDispatcher,
    PHPUnit_Framework_TestCase;

class AbstractProject extends PHPUnit_Framework_TestCase
{
    protected $backupGlobalsBlacklist = array('project','symfony_auto_loader');


    protected $faker_dir = 'myproject';

    /**
      *  @var Faker\Project 
      */
    public static $project;

    /**
      *  Class Constructor 
      */
    public function __construct()
    {
        # remove Faker project directory
        $path = '/var/tmp/' . $this->faker_dir;

        self::recursiveRemoveDirectory($path,true);
        
        $project = self::$project;
        $project->setPath($this->getMockedPath());

        $project['loader']->setExtensionNamespace(
               'Faker\\Extension' , $project->getPath()->get()
        );
       
        if(isset($project['data_path']) === false) {
            $project['data_path'] = new \Faker\Path(__DIR__.'/../../data');
        }
    }



    public function setUp()
    {
        #test build
        $this->createProject(self::$project,$this->getSkeltonIO());
    }


    public function tearDown()
    {

        #remove Faker project directory
        $path = '/var/tmp/' . $this->faker_dir;

        self::recursiveRemoveDirectory($path,true);

    }

    /**
      *  Will Fetch the project object
      *
      *  @return Faker\Project
      */
    public function getProject()
    {
        return self::$project;
    }


    //  -------------------------------------------------------------------------
    # Skelton IO

    public function getSkeltonIO()
    {
        $skelton = new Io(realpath(__DIR__.'/../../skelton'));


        return $skelton;
    }


    //  -------------------------------------------------------------------------
    # create project

    public function createProject(Project $project,Io $skelton_folder)
    {
        # Setup new project folder since our build method does not
        mkdir($project->getPath()->get());
        $project_folder = new Io($project->getPath()->get());
        $project->build($project_folder,$skelton_folder,new NullOutput());
    }


    //  -------------------------------------------------------------------------
    # Helper Functions

     /**
      *  function  recursiveRemoveDirectory
      *
      *  @param string absolute path
      *  @param boolean true to empty directory only defaults to false
      *  @access public
      *  @source http://lixlpixel.org/recursive_function/php/recursive_directory_delete/
      */
    public static function recursiveRemoveDirectory($directory, $empty = false)
    {
            // if the path has a slash at the end we remove it here
            if(substr($directory,-1) == '/') {
                    $directory = substr($directory,0,-1);
            }

            // if the path is not valid or is not a directory ...
            if(!file_exists($directory) || !is_dir($directory)) {
                    // ... we return false and exit the function
                    return false;

            // ... if the path is not readable
            } elseif(!is_readable($directory)) {
                    // ... we return false and exit the function
                    return false;

            // ... else if the path is readable
            } else {

                    // we open the directory
                    $handle = opendir($directory);

                    // and scan through the items inside
                    while (FALSE !== ($item = readdir($handle))) {
                            // if the filepointer is not the current directory
                            // or the parent directory
                            if($item != '.' && $item != '..') {
                                    // we build the new path to delete
                                    $path = $directory.'/'.$item;

                                    // if the new path is a directory
                                    if(is_dir($path)) {
                                            // we call this function with the new path
                                            self::recursiveRemoveDirectory($path,$empty);

                                    // if the new path is a file
                                    } else{
                                            // we remove the file
                                            unlink($path);
                                    }
                            }
                    }
                    // close the directory
                    closedir($handle);

                    // if the option to empty is not set to true
                    if($empty == true) {
                            // try to delete the now empty directory
                            if(!rmdir($directory)) {
                                    // return false if not possible
                                    return false;
                            }
                    }
                    // return success
                    return true;
            }
    }

    // ------------------------------------------------------------

    protected function getMockedPath()
    {
        return new Path('/var/tmp/'.$this->faker_dir);

    }

    //  -------------------------------------------------------------------------

    protected function getMockConfigEntityParm()
    {
        return array(
            'db_type' => 'pdo_mysql' ,
            'db_schema' => 'example' ,
            'db_user' => 'bob' ,
            'db_password' => 'pass',
            'db_host' => 'localhost' ,
            'db_port' => 3306 ,
            );
    }

   

    //  -------------------------------------------------------------------------
    # Get Mock OuputInterface
    

    protected function getMockOuput()
    {
        
        //return new \Symfony\Component\Console\Output\ConsoleOutput();
        
        return $this->getMock('\Symfony\Component\Console\Output\OutputInterface',array());
    }

    //  -------------------------------------------------------------------------
    # Get Mock MonoLog
    
    
    protected function getMockLog()
    {
        $sysLog = new \Monolog\Handler\TestHandler();
    
        // Create the main logger of the app
        $logger = new \Monolog\Logger('error');
        $logger->pushHandler($sysLog);
    
        #assign the log to the project
        return $logger;
    
    }
    
}
/* End of File */
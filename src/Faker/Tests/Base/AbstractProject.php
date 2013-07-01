<?php
namespace Faker\Tests\Base;

use Faker\Project,
    Faker\Io\Io,
    Faker\Path,
    Faker\Bootstrap,
    Faker\Components\Engine\Original\Collection,
    Symfony\Component\Console\Output\NullOutput,
    Symfony\Component\Filesystem\Filesystem,
    Symfony\Component\Finder\Finder,
    Symfony\Component\EventDispatcher\EventDispatcher,
    PHPUnit_Framework_TestCase;

class AbstractProject extends PHPUnit_Framework_TestCase
{
    
    protected $backupGlobalsBlacklist = array('project');
    
    protected $faker_dir = 'myproject';

    /**
      *  @var Faker\Project 
      */
    public static $project;

    
    //  ----------------------------------------------------------------------------
    
    /**
      *  Class Constructor 
      */
    public function __construct()
    {
        $this->preserveGlobalState = false;
        $this->runTestInSeperateProcess = true;
        $this->getProject()->setPath($this->getMockedPath());
        $this->destoryProject($this->getProject());
    }


    public function setUp()
    {
        $this->createProject($this->getProject(),$this->getSkeltonIO());
    }


    public function tearDown()
    {
        $project = $this->getProject();
        
        # rest default event dispatcher
        $project['event_dispatcher'] = new \Symfony\Component\EventDispatcher\EventDispatcher();
        
        $this->destoryProject($project);
    }

    //  ----------------------------------------------------------------------------
    
    /**
      *  Will Fetch the project object
      *
      *  @return Faker\Project
      */
    public function getProject()
    {
        if(self::$project === null) {
            $boot    = new Bootstrap();
            self::$project = $boot->boot('1.0.3-test',null);
        }
        
        return self::$project;
    }


    protected function getSkeltonIO()
    {
        return new Io(realpath(__DIR__.'/../../../../skelton'));
    }

    
    protected function getMockedPath()
    {
        return new Path('/var/tmp/'.$this->faker_dir);
    }


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
    

    protected function getMockOuput()
    {
        return $this->getMock('\Symfony\Component\Console\Output\OutputInterface',array());
    }

    
    protected function getMockLog()
    {
        $sysLog = new \Monolog\Handler\TestHandler();
        // Create the main logger of the app
        $logger = new \Monolog\Logger('error');
        $logger->pushHandler($sysLog);
        #assign the log to the project
        return $logger;
    }

    //  -------------------------------------------------------------------------
    

    public function createProject(Project $project,Io $skelton_folder)
    {
        $fs = new Filesystem();
        
        # Setup new project folder since our build method does not
        if(is_dir($project->getPath()->get()) === false) {
            $fs->mkdir($project->getPath()->get());
        }
        
        $project->build(new Io($project->getPath()->get()),$skelton_folder,new NullOutput());
        
        
        $project['loader']->setExtensionNamespace(
               'Faker\\Extension' , $project->getPath()->get()
        );
       
        if(isset($project['data_path']) === false) {
            $project['data_path'] = new \Faker\Path(__DIR__.'/../../../../data');
        }
        
        $project->getPath()->loadExtensionBootstrap();            
        
        return $this;
    }


    public function destoryProject(Project $project)
    {
        $finder = new Finder();
        $fs     = new Filesystem();
        
        $fs->remove($finder->directories()->in($project->getPath()->get()));
        
        return $this;
    }

    // ------------------------------------------------------------

   
    
}
/* End of File */
<?php
namespace Faker;

class Path
{
    /**
      *  @var $path a parsed path of the project
      */
    protected $path;

    /**
      *  function get
      *
      *  fetches the projects path
      *
      *  @return string the project path
      *  @access public
      */
    public function get()
    {
        return $this->path;
    }
    
    /**
      *  Set the path function
      *
      *  @return void
      *  @access public
      *  @param string $path the path to project folder
      */
    public function set($path)
    {
        $this->path = $path;
        
    }
    

    //  -------------------------------------------------------------------------


    public function __construct($path = '')
    {
         if($path === '') {
            $this->path = getcwd();
         }
        else {
            $this->path = $path;
        }

    }

    //  -------------------------------------------------------------------------


    /**
      *  function parse
      *
      *  Will parse argument given by the user and
      *  attempt to match to a realpath
      *
      *  @parma $project_folder the path given by the user
      *  @return mixed a full path or false otherwise
      *  @access public
      */
    public function parse($project_folder)
    {
        # strip the last char if its a DIRECTORY_SEPARATOR
        $project_folder = rtrim($project_folder,DIRECTORY_SEPARATOR);
    
        # Step 1. Check for empty path or dot operator.
        if ($project_folder === '.' || $project_folder === '') {
            // must mean use current directory
            $project_folder = getcwd();
        }
        else {

            # Step 2. check if path is relative and resolve it with realpath
            if(strpos('..',$project_folder) == 0) {
                $project_folder =  realpath($project_folder);
            }
            
            # Step 3. Check for existance of the directory if not found assume child of the Current Working Directory
            if(!is_dir($project_folder)) {
               #if where still false lets append cwd to what we have
               $project_folder  = getcwd() . DIRECTORY_SEPARATOR . ltrim($project_folder,DIRECTORY_SEPARATOR); 
            } 

        }
        
        $this->path = $project_folder;

        
        return $this->path;
    }
    
    /**
      *  Load the Extensions Bootstrap file
      *
      *  @access public
      *  @return void
      */
    public function loadExtensionBootstrap()
    {
        $extension_bootstrap = $this->path . DIRECTORY_SEPARATOR . 'extension'. DIRECTORY_SEPARATOR .'bootstrap.php';
        require $extension_bootstrap;
    }
    
}
/* End of File */

<?php
namespace Faker;

use  Symfony\Component\Console\Output\OutputInterface,
     Symfony\Component\Finder\Finder,
     Pimple,
     Faker\Exception as FakerException,
     Faker\Path;

class Project extends Pimple
{

    /**
      *  function getPath
      *
      *  @return \Faker\Path
      *  @access public
      */
    public function getPath()
    {
         return $this['project_path'];
    }
     
    /**
      *  function setPath
      *
      *  @access public
      *  @param \Faker\Path
      */ 
    public function setPath(Path $path)
    {
        $this['project_path'] = $path;  
    }
    
    
    /**
      *  Function getDataPath
      *
      *  @return \Faker\Path
      *  @access public
      */
    public function getDataPath()
    {
          return $this['data_path'];
     
    }
    
    

    //  -------------------------------------------------------------------------
    # Constructor

    /**
     * Function __construct
     *
     * Class Constructor
     *
     *  @return void
     *  @param \Faker\path $path
     */
    public function __construct(\Faker\Path $path)
    {
        $this['project_path'] = $path;
        $this['default'] = 'default';
        $this['schema_name'] = 'default';
    }


    //  -------------------------------------------------------------------------
    # Creats a new project


    public function build(\Faker\Io\Io $folder, \Faker\Io\Io $skelton, \Symfony\Component\Console\Output\OutputInterface $output)
    {

        $mode = 0777;

        #check if root folder exists
        if(is_dir($folder->getBase()) === false) {
            throw new FakerException('Root directory does not exist');
        }

        #make config folders
        $config_path = $folder->getBase() . DIRECTORY_SEPARATOR .'config';

        if (mkdir($config_path,$mode) === TRUE) {
                $output->writeln('<info>Created Config Folder</info>');

                //copy files into it
                $files = $skelton->iterator('config');

                foreach($files as $file){
                    if($this->copy($file,$config_path) === TRUE) {
                       $output->writeln('++ Copied '.basename($file));
                    }

                }

            }


            #make template folder
            $template_path = $folder->getBase() . DIRECTORY_SEPARATOR . 'template';
            if (mkdir($template_path,$mode) === TRUE) {
                $output->writeln('<info>Created Template Folder</info>');

                 //copy files into it
                $files = $skelton->iterator('template');


                foreach($files as $file){
                    if($this->copy($file,$template_path) === TRUE) {
                       $output->writeln('++ Copied '.basename($file));
                    }

                }


            }
            
             #make extension extension folder
            $extension_path = $folder->getBase() . DIRECTORY_SEPARATOR . 'extension';
            if (mkdir($extension_path,$mode) === TRUE) {
                $output->writeln('<info>Created Extension Folder</info>');

                 //copy files into it
                $files = $skelton->iterator('extension');

                foreach($files as $file){
                    if($this->copy($file,$extension_path) === TRUE) {
                          $output->writeln('++ Copied '.basename($file));
                    }
                }


            }
            
            #make dump folder
            $dump_path = $folder->getBase() . DIRECTORY_SEPARATOR . 'dump';
            if (mkdir($dump_path,$mode) === TRUE) {
                $output->writeln('<info>Created Dump Folder</info>');

            }
            
          #make sources folder
          $sources_path = $folder->getBase() . DIRECTORY_SEPARATOR . 'sources';
          if (mkdir($sources_path,$mode) === TRUE) {
               $output->writeln('<info>Created Sources Folder</info>');

          }
            

    }

    //-----------------------------------------------------------------------------

    /**
     * Copy a path to destination, check if file,directory or link
     * @param string $source      The Source File
     * @param string $destination The Destination File
     * @return boolean
     */
    public function copy(\Symfony\Component\Finder\SplFileInfo $source,$destination)
    {
        $new_path = $destination . DIRECTORY_SEPARATOR. $source->getRelativePathname();

        #Test if Source is a link
        if($source->isLink()) {
           return symlink($source,$new_path);
        }

        # Test if source is a directory
        if($source->isDir()){
            return mkdir($new_path);
        }

        #Test if Source is a file
        if($source->isFile()) {
            return copy($source,$new_path);

        }

        return FALSE;

    }

    //  -----------------------------------------------------------------------------
    # Database

    /**
      *  function getDatabase
      *
      *  @access public
      *  @return  the configured database handler
      */
    public function getDatabase()
    {
        return $this['database'];
    }
   
   

    //  -------------------------------------------------------------------------
    # Manager loaders


    /**
      *  function getConfigManager
      *
      *  @access public
      *  @return \Faker\Components\Config\Manager an instance of the config component manager
      */
    public function getConfigManager()
    {
        return $this['config_manager'];
    }

   
    /**
      *  function getTemplateManager
      *
      *  @access public
      *  @return \Faker\Components\Templating\Manager an instance of the templating component manager
      */
    public function getTemplatingManager()
    {
        return $this['template_manager'];
    }

    /**
      *  function getFakerManager
      *
      *  @access public
      *  @return \Faker\Components\Faker\Manager an instance of the Faker component manager
      */
    public function getFakerManager()
    {
        return $this['faker_manager'];
    }

     /**
      *  function getFakerManager
      *
      *  @access public
      *  @return \Faker\Components\Faker\Manager an instance of the Faker component manager
      */
    public function getWritterManager()
    {
        return $this['writter_manager'];
    }    
    

    //  -------------------------------------------------------------------------
    # Debug Log

    /**
      *  function getLogger
      *
      *  @access public
      *  @return \Monolog\Logger an instance of the debug logger
      */
    public function getLogger()
    {
        return $this['logger'];
    }

    //  -------------------------------------------------------------------------
    # Config Name

    /**
      * function getConfigName
      *
      * @access public
      * @return string the name of the config file to use
      */
    public function getConfigName()
    {
        return  \Faker\Components\Config\Loader::DEFAULTNAME . \Faker\Components\Config\Loader::EXTENSION;
    }


     //------------------------------------------------------------------

     /**
      *  Function getConfigFile
      *
      *  @access public
      *  @return \Migration\Components\Config\Entity
      */ 
    public function getConfigFile()
    {
          return $this['config_file'];
    }
    
    public function hasConfigSet()
    {
          return $this['has_config'];
    }
   
    //  -------------------------------------------------------------------------
    # Symfony Console

    /**
      *  function getConsole
      *
      *  @access public
      *  @return \Faker\Command\Base\Application
      */
    public function getConsole()
    {
        return $this['console'];
    }

    //  -------------------------------------------------------------------------
    # Detect project folder

    /**
      *  static function detect
      *
      *  Will check if a project directory given in path
      *  matches the folder standard folder layout
      *
      *  @param string $path the path to check
      *  @return boolean true if folder internals match expected layout
      */
    public static function detect($path)
    {
        $path = rtrim($path,'/');

        #check for config folder
        if(is_dir($path . DIRECTORY_SEPARATOR . 'config') === false) {
            return false;
        }

        #check for dump folder
        if(is_dir($path . DIRECTORY_SEPARATOR . 'dump') === false) {
            return false;
        }

        #check for template folder
        if(is_dir($path . DIRECTORY_SEPARATOR . 'template') === false) {
            return false;
        }
        
        #check for extension
        if(is_dir($path . DIRECTORY_SEPARATOR . 'extension') === false) {
            return false;
        }
        
         #check for sources
        if(is_dir($path . DIRECTORY_SEPARATOR . 'sources') === false) {
            return false;
        }

        return true;
    }
}
/* End of File */

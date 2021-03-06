<?php
namespace Faker\Components\Config;

use Faker\Io\IoInterface;
use Faker\Project;


/*
 * class Manager
 */

class Manager 
{

    protected $loader;

    protected $writer;

    protected $io;
    
    /**
      *  @var Migration\Project 
      */   
    protected $project;

    //  -------------------------------------------------------------------------
    # Class Constructor

    /*
     * __construct()
     * @param $arg
     */
    public function __construct(IoInterface $io,Project $di)
    {
        $this->io = $io;
        $this->project = $di;   

    }
    //  -------------------------------------------------------------------------
    # Congfig file loader

    /**
      *  function getLoader
      *
      *  return with this components loader object, is used to find database
      *  config files under the config directory of your project
      *
      *  @access public
      *  @return \Migration\Components\Config\Loader
      */
    public function getLoader()
    {
        if($this->loader === NULL) {
            $this->loader = new Loader($this->io,$this->project['logger'],$this->project['console_output'],null);
        }

        return $this->loader;
    }

    //  -------------------------------------------------------------------------
    # Config Writter

    /**
      * function getWriter
      *
      * return this components file writer object, which is used to write
      * config files into the project directory
      *
      * @access public
      * @return \Migration\Components\Config\Writer
      */
    public function getWriter()
    {
        if($this->writer === NULL) {
            $this->writer = new Writer($this->io,$this->project['logger'],$this->project['console_output'],null);
        }

        return $this->writer;
    }

    //  -------------------------------------------------------------------------
    
    public function getCLIFactory()
    {
        return $this->project['config_cli_factory'];
    }
    
    
    public function getDSNFactory()
    {
        
        return $this->project['config_dsn_factory'];
    }
    
    //---------------------------------------------------------------------------
}
/* End of File */

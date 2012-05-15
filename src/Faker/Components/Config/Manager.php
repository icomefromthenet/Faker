<?php
namespace Faker\Components\Config;

use Faker\Io\IoInterface;
use Faker\Project;
use Faker\Components\ManagerInterface;

/*
 * class Manager
 */

class Manager implements ManagerInterface
{

    protected $loader;

    protected $writer;

    protected $io;
    
    /**
      *  @var Faker\Project 
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
      *  @return \Faker\Components\Config\Loader
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
      * @return \Faker\Components\Config\Writer
      */
    public function getWriter()
    {
        if($this->writer === NULL) {
            $this->writer = new Writer($this->io,$this->project['logger'],$this->project['console_output'],null);
        }

        return $this->writer;
    }

    //  -------------------------------------------------------------------------

}
/* End of File */

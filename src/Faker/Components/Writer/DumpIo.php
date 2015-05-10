<?php
namespace Faker\Components\Writer;

use Faker\Io\IoInterface;

/*
 * class Io
 */
class DumpIo implements IoInterface
{

    protected $base;
    
    /*
     * __construct()
     *
     * @param string $base_folder the path to a project
     * @return void
     */

    public function __construct($base_folder)
    {
        $this->base = $base_folder;
    }

    //----------------------------------------------------------------------

    /**
     * Builds a Path inside the project
     *
     * @param array $folders array of subfolders to join together
     * @return string built path
     */

    public function path($folders = null)
    {
        return null;
    }



    //----------------------------------------------------------------------


    /**
     * Loads a file from the path
     *
     *  @param string $name filename
     *  @param mixed $folders array of folders to append
     *  @param boolean $object true to return SplFileInfo;
     *  @return SplFileInfo | string
     *  @access public
     */
    public function load($name,$folders,$object = false)
    {
        return null;
    }

    //  -------------------------------------------------------------------------


    /**
      *  function mkdir
      *
      *  create a directory relative to the base dir
      *
      *  @access public
      *  @return boolean
      *  @throws DirectoryExistsException
      */
    public function mkdir($name)
    {
        return true;
    }


    //----------------------------------------------------------------------

    /**
      * Check if a config is found using the supplied name
      *
      * @param string $name the config name
      * @param mixed $folders extra folder to append to path
      * @return boolean true if found false otherwise
      */

    public function exists($name,$folders = null)
    {
        return true;
    }


    //----------------------------------------------------------------------


    
    public function write($filename,$folders,$content,$overrite = FALSE)
    {
        return null;
    }


    //----------------------------------------------------------------------

    

    public function iterator($path = NULL)
    {
        return null;
    }


    //----------------------------------------------------------------------


   

    public function contents($name, $folders = null)
    {
        return null;
    }



    //  -------------------------------------------------------------------------


    /**
     * Returns the base project path
     *
     * @return string project path
     */
    public function getBase()
    {
        return $this->base;
    }


    /**
     * Sets the project path
     *
     * @param string $path the project path
     */
    public function setBase($path)
    {
        $this->base = $base;
    }


    //---------------------------------------------------------------------

}
/* End of File */

<?php
namespace Faker\Components\Templating;

use \Twig_Source;
use \Twig_LoaderInterface;
use Faker\Io\FileNotExistException;

/*
 * class Loader
 */
class TwigLoader implements Twig_LoaderInterface
{
    
    
    protected function resolvePath($name, $folders = [])
    {
        $copy = $folders;
        $path = $this->getIo()->path($folders);

        # Base folder for schema/template
        if($this->getIo()->exists($name,$folders) === false) {
            
            $path = null;
            
            # Not found search base dir for template
            foreach($folders as $folder)   {
                array_pop($copy);
                if($this->getIo()->exists($name,$copy)) {
                   return $copy;
                }
            }
        }
        
        return $folders;
    }
    
    
    /**
     * Gets the source code of a template, given its name.
     *
     * @param  string $name string The name of the template to load
     *
     * @return string The template source code
     */
    public function getSourceContext($name)
    {
        $folders  = explode('/',$name);
        $filename = array_pop($folders);
        $path     = $this->resolvePath($filename,$folders);
        
        if(!$path) {
            throw new FileNotExistException('Can not find file named: '. $filename);
        }
        
        $source = $this->getIo()->contents($filename,$path);    
        
        return new Twig_Source($source,$name,implode(DIRECTORY_SEPARATOR,$path));

    }

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param  string $name string The name of the template to load
     *
     * @return string The cache key
     */
    function getCacheKey($name)
    {
        return $name;
    }

    /**
     * Returns true if the template is still fresh.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
     */
    public function isFresh($name, $time)
    {
        return true;
    }
    
    /**
     *  Return true if tempate of given name exists
     * 
     * @return boolean
     */ 
    public function exists($name)
    {
        $folders = explode('/',$name);
        $name = array_pop($folders);
        
        $path = $this->resolvePath($name, $folders);
        
        if(!$path) {
            return false;
        }
        
        return true;
    }


    /*
     * __construct()
     *
     * @param Io the input output class
     * @return void
     * @access public
     */
    public function __construct(Io $io)
    {
        $this->setIo($io);
    }


    //--------------------------------------------------------------------
    /**
     * Input Output controller
     *
     *  @var Io
    */
    protected $io;

   /**
    * Fetches the Io Class
    *
    * @return Io
    */
    public function getIo()
    {
        return $this->io;
    }

    /**
    * Sets the IO class
    *
    *  @param Io $io
    */
    public function setIo(Io $io)
    {
        $this->io = $io;

        return $this;
    }


    //---------------------------------------------------------------------


}
/* End of File */

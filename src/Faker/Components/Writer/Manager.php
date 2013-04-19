<?php
namespace Faker\Components\Writer;

use Faker\Io\IoInterface,
    Faker\Project,
    Faker\Io\FileNotExistException;

/*
 * class Manager
 */

class Manager 
{

    /**
      *  @var Faker\Project; 
      */
    protected $project;

    /**
      *  @var Faker\Components\Writer\Io 
      */
    protected $io;
   
    //  -------------------------------------------------------------------------
    # Class Constructor

    /*
     * __construct()
     * @param $arg
     */

     /**
       *  function __construct
       *
       *  class constructor
       *
       *  @access public
       */
    public function __construct(IoInterface $io,Project $di)
    {
        $this->io = $io;
        $this->project = $di;
    }


    //  -------------------------------------------------------------------------
    # File loader

    public function getLoader()
    {
        throw new RuntimeException('not implemented');
    }

    //  -------------------------------------------------------------------------
    # Writter

    /**
      * function getWriter
      *
      * return this components file writer object, which is used to write
      * config files into the project directory
      *
      * @access public
      * @return \Faker\Components\Config\Writer
      */
    public function getWriter($platform,$formatter)
    {
       return new Writer($this->getStream($platform,$formatter),$this->getCache(),$this->getCacheMax());
    }
    
    //-------------------------------------------------------------------------------
    # encoder
    
    /**
      *  Fetches the character encoder
      *
      *  @access public
      *  @param string $in
      *  @param string $out;
      *  @return Faker\Components\Writer\Encoding
      */
    public function getEncoder($in,$out)
    {
        return new Encoding($in,$out);
    }

    //  -------------------------------------------------------------------------
    # Internal Dependecies
    
    public function getCache()
    {
        return new Cache();
    }

    public function getLimit()
    {
      return new Limit($this->getLinesInFile());
    }

    public function getStream($platform,$formatter)
    {
        return new Stream($this->getHeaderTemplate($platform,$formatter),
                          $this->getFooterTemplate($platform,$formatter),
                          $this->getSequence($platform, 'schema', 'table', 'sql'),
                          $this->getLimit(),
                          $this->io,
                          $this->getEncoder('UTF-8','UTF-8')
                        );
    }
    
    public function getSequence($prefix, $body, $suffix, $extension,$format = '{prefix}_{body}_{suffix}_{seq}.{ext}')
    {
        return new Sequence($prefix, $body, $suffix, $extension,$format);    
    }
    

    //  -------------------------------------------------------------------------
    # Properties    

    protected $lines_in_file = 500;
    
    public function setLinesInFile($lines)
    {
        $this->lines_in_file = (integer) $lines;
    }
    
    public function getLinesInfile()
    {
        return $this->lines_in_file;
    }

    //  -------------------------------------------------------------------------
    
    protected $cache_max = 1000;
    
    public function setCacheMax($max)
    {
        $this->cache_max = (integer) $max;
    }
    
    public function getCacheMax()
    {
        return $this->cache_max;
    }
    
    //  -------------------------------------------------------------------------
    
    protected $header_template ='header_template.twig';

    
    public function getHeaderTemplate($platform,$formatter)
    {
        
        try {
            # try and load the file
               
            $template = $this->project['template_manager']
                    ->getLoader()
                    ->load($formatter. DIRECTORY_SEPARATOR .$platform. DIRECTORY_SEPARATOR .$this->header_template); 
       
        } catch (FileNotExistException $e) {
            #try and load the fallback
            
            $template = $this->project['template_manager']
                    ->getLoader()
                    ->load($formatter. DIRECTORY_SEPARATOR .$this->header_template); 
        
        }
        
        return  $template;
    }
    
    public function setHeaderTemplate($template)
    {
        $this->header_template = $template;
    }
    
    
    //  -------------------------------------------------------------------------
    
    protected $footer_template = 'footer_template.twig';
    
    
    public function setFooterTemplate($template)
    {
        $this->footer_template = $template;
    }
    
    public function getFooterTemplate($platform,$formatter)
    {
        
        try {
            # try and load the file
            
            $template = $this->project['template_manager']
                    ->getLoader()
                    ->load($formatter. DIRECTORY_SEPARATOR .$platform. DIRECTORY_SEPARATOR .$this->footer_template);
            
        } catch (FileNotExistException $e) {
             # try fall back 
                
            $template = $this->project['template_manager']
                    ->getLoader()
                    ->load($formatter. DIRECTORY_SEPARATOR .$this->footer_template);
        }
        
        return $template;
            
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */

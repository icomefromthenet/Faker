<?php
namespace Faker\Components\Writer;

use Faker\Components\Writer\Io;
use Faker\Components\Writer\Limit;
use Faker\Components\Writer\Sequence;
use Faker\Components\Templating\Template;

class Stream implements WriterInterface
{

    /**
      * The path object
      *
      * @var \Faker\Components\Writer\Io
      */
    protected $io;


    /**
      * Template to use for each file during writting
      *
      * @var Faker\Components\Templating\Template
      */
    protected $header_template;

     /**
      * Template to use for each file during writting
      *
      * @var Faker\Components\Templating\Template
      */
    protected $footer_template;


    /**
     * The maxium number of lines to write to a file
     *
     * @var \Faker\Components\Writer\Limit
     */
    protected $write_limit;


    /**
     * Instace of the file sequence iterator
     *
     * @var \Faker\Components\Writer\Sequence
     */
    protected $file_sequence;

    /**
     * The file hander
     *
     * @var SplFileObject;
     */
    protected $file_handle = NULL;

    /**
      *  @var Faker\Components\Writer\Encoding the iconv char encoder 
      */
    protected $encoder;
    
    /**
     * Get file handle (SplFileInfo -> SplFileObject)
     * 
     * @param string the fileName
     * @access protected
     * @return SplFileObject
     */ 
    protected function getFile($file_name) 
    {
        # write template (will overrite file)
        $this->getIo()->write($file_name,'','',true);
        
        return $this->getIo()->load($file_name,'',true)->openFile('a');
    }
    
    

    public function __construct(Template $header_template, Template $footer_template, Sequence $file_sequence, Limit $write_limit, Io $path,Encoding $encoder)
    {
        $this->encoder         = $encoder;
        $this->header_template = $header_template;
        $this->footer_template = $footer_template;
        $this->file_sequence   = $file_sequence;
        $this->write_limit     = $write_limit;
        $this->io              = $path;

    }


    public function write($line)
    {
        # file handler is null  
    
        if($this->file_handle === null) {
            
            # increment the file sequence
            $this->getSequence()->add();
            
            # reset the limit
            $this->getLimit()->reset();
            
            # generate new template string
            $file_name = $this->getSequence()->get();
            
            $this->file_handle = $this->getFile($file_name);
            
            $this->writeHeader();
        }
        
        # write to the file (SplFileObject)
        $this->writeOut($line);
           
        # increment the limit
        $this->getLimit()->increment();
    
        # if at limit write footer template
        
        if($this->getLimit()->atLimit() === true) {
           
            $this->flush();
        }
    }
    
    
    public function writeHeader()
    {
        # header template
        $header = (string) $this->header_template->render();
        
        $this->writeOut($header);
       
    }
    
    public function writeFooter()
    {
        # footer template
        $footer = (string) $this->footer_template->render();
       
        $this->writeOut($footer);
       
        
    }
        
    public function flush()
    {
        # write footer to file
        if($this->file_handle !== null) {
            $this->writeFooter();
            $this->file_handle->fflush();
            $this->file_handle = null;
        }
    }
    
    /**
      *  Write out to the stream object encoding first
      *
      *  @access protected
      *  @param string $str
      *  @return void
      */
    protected function writeOut($str)
    {
        $this->file_handle->fwrite($this->encoder->encode($str));
    }
    
    
    //  -------------------------------------------------------------------------
    # properties Accessors
    
    /**
      *  Fetch the writers sequence
      *
      *  @access public
      *  @return Faker\Components\Writer\Sequence
      */
    public function getSequence()
    {
        return $this->file_sequence;
    }
    
    /**
      *  Fetch the IO
      *
      *  @access public
      *  @return Faker\Components\Writer\Io;
      */
    public function getIo()
    {
        return $this->io;
    }
    
    /**
      *  Fetch the Write Limiter
      *
      *  @access public
      *  @return Faker\Components\Writer\Limit;
      */
    public function getLimit()
    {
        return $this->write_limit;
    }
    
    /**
      *  Fetch the header template
      *
      *  @access public
      *  @return Faker\Components\Templating\Template
      */
    public function getHeaderTemplate()
    {
        return $this->header_template;        
    }
    
    /**
      *  Fetch the footer template
      *
      *  @access public
      *  @return Faker\Components\Templating\Template
      */
    public function getFooterTemplate()
    {
        return $this->footer_template;
    }
    
    //  -------------------------------------------------------------------------

    /**
      *  Fetch the output encoder
      *
      *  @access public
      *  @return Faker\Components\Writer\Encoding;
      */
    public function getEncoder()
    {
        return $this->encoder;
    }
    
}
/* End of File */

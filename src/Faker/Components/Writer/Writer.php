<?php
namespace Faker\Components\Writer;

use Faker\Components\Writer\Io;
use Faker\Components\Writer\Cache;
use Faker\Components\Writer\Exception as WriterException;
use Faker\Components\Writer\Stream;

/**
  *  Class Writer
  */
class Writer implements WriterInterface
{

    /**
      *  @var  Faker\Components\Writer\Cache
      */
    protected $cache;

    /**
      *  @var Faker\Componenets\Writer\Stream 
      */
    protected $stream;

    /**
      *  @var integer the number of files to cache 
      */
    protected $cache_limit;
    
  

     //----------------------------------------------------------------

    /**
      * Write a line to a file stream
      *
      * @param string $line
      * @return void
      * @access public
      * @throws :: WriterException()
      */
    public function write($line)
    {
        try {
        
            # add to cache
            $this->cache->write($line);
            
            # test cache limit
            if($this->cache->count() >= $this->cache_limit ) {
                foreach($this->cache as $line) {
                    $this->writeOut($line);             
                }
                
                # remove lines from cache
                $this->cache->flush();   
            }
            
        }
        catch(\Exception $e) {
            throw new WriterException($e->getMessage());
        }

    }

    //  -------------------------------------------------------------------------
    # Flush (run when finish writing)
    
    /**
      *  Flush the stream and cache
      *
      *  @access public
      *  @throws :: WriterException()
      */    
    public function flush()
    {
       try {
                
            # empty the cache into the stream 
            foreach($this->cache as $line) {
               $this->writeOut($line);            
            }
            # remove all lines from cache
            $this->cache->flush();
            
            # tell the stream to close and write footers
            $this->stream->flush();
            
        }
        catch(\Exception $e) {
            throw new WriterException($e->getMessage());
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
        $this->stream->write($str);
    }
    
    
  //------------------------------------------------------------------

   /**
    * Class Constructor
    *
    */
    public function __construct(Stream $stream, Cache $cache, $cache_limit = 500)
    {
        $this->stream      = $stream;
        $this->cache       = $cache;
        $this->cache_limit = $cache_limit;
        
    }

    //  -------------------------------------------------------------------------
    # Property Accessors
   
    /**
      *  Fetch the writer stream
      *
      *  @access public
      *  @return Faker\Components\Writer\Stream
      */
    public function getStream()
    {
        return $this->stream;        
    }
    
    /**
      *  Fetch the writers cache
      *
      *  @access public
      *  @return Faker\Components\Writer\Cache
      */ 
    public function getCache()
    {
        return $this->cache;
    }
    
    /**
      *  Fetch the cache limit
      *
      *  @access public
      *  @return integer the cache limit
      */
    public function getCacheLimit()
    {
        return $this->cache_limit;
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */
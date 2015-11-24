<?php
namespace Faker\Components\Writer;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Faker\Components\Writer\Io;
use Faker\Io\Exception as IOException;
use Faker\Components\Writer\Limit;
use Faker\Components\Writer\Sequence;
use Faker\Components\Templating\Template;

/**
 * Overrides stream output methods to push data into a database instead of a file.
 * 
 * This calass with batch output until flush it write an entire file to the
 * database, mean that any headers and footers will be incldued with every call to
 * flush, so don't put DDL into them.
 * 
 * Though good place to place a transation into.
 * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.5
 */ 
class DatabaseStream extends Stream implements WriterInterface
{

    /**
     * @var Connection
     */ 
    protected $databaseConn;


    protected $cache;
    
    
    /**
     * Override the file maker and return database instance
     * 
     * @return Doctrine\DBAL\Connection
     */ 
    protected function getFile($file_name) 
    {
        return $this->getDatabase();
    }
    
    
    /**
     * Write the cache to the database as a single statement
     */ 
    public function flush()
    {
        try {
        
            $result = true;
            
            # used in parent class
            if(count($this->cache) > 0) {
            
                $this->writeFooter();
                $this->file_handle = null;
                
                # write to database
                $conn =  $this->getDatabase();
                $sSql = implode(' ',$this->cache);

           
                
                if(!empty($sSql)) {
                    $result = $conn->exec($sSql);
                }
            
            }
        
            # clear cache values 
            unset($this->cache);
            $this->cache = array();
            
        } catch(DBALException $e) {
            
            throw new IOException($e->getMessage(),0,$e);
            
        }
        
        return $result;
    }
    
    /**
      *  Store the line in the internal cache
      *
      *  @access protected
      *  @param string $str
      *  @return void
      */
    protected function writeOut($str)
    {
        $this->cache[] = $str;
    }
    
    
    // -------------------------------------------------------------------------
    
    /**
      *  Fetch the database connection
      *
      *  @access public
      *  @return Doctrine\DBAL\Connection;
      */
    public function getDatabase()
    {
        return $this->databaseConn;
    }
    
    /**
      *  Fetch the database connection
      *
      *  @access public
      *  @return Void;
      *  @param Doctrine\DBAL\Connection $db    the database connection
      */
    public function setDatabase(Connection $db) 
    {
        $this->databaseConn = $db;
    }
    
}
/* End of File */

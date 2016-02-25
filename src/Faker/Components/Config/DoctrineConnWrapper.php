<?php
namespace Faker\Components\Config;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;



/**
 * This is a wrapper class that  adds the collection  pool to standrd doctrine DBAL connections
 * 
 */  
class DoctrineConnWrapper extends Connection
{
   
   protected $fakerConnectionPool;
   
   protected $fakerReadOnlyConnection;
   
   /**
    * Fetch the connection pool
    * 
    * @return Faker\Components\Config\ConnectionPool
    * @access public
    */ 
   public function getFakerConnectionPool()
   {
       return $this->fakerConnectionPool;
   }
   
   /**
    * Sets the connection pool
    * 
    * @param Faker\Components\Config\ConnectionPool $pool   the database pool
    * @return void
    * @access public
    */ 
   public function setFakerConnectionPool(ConnectionPool $pool)
   {
       $this->fakerConnectionPool = $pool;
   }
   
   /**
    * Gets the read only config value
    * 
    * @return boolean
    * @access public
    */ 
   public function getFakerReadOnlyConnection()
   {
      return $this->fakerReadOnlyConnection;
   }
   
   /**
    * Sets the read only config value
    * 
    * @return boolean
    * @access public
    * @param  boolean $bReadOnly 
    */ 
   public function setFakerReadOnlyConnection($bReadOnly)
   {
      $this->fakerReadOnlyConnection = $bReadOnly;
   }
   
   
}
/* End of Class */

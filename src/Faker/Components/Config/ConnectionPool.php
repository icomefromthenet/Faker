<?php
namespace Faker\Components\Config;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;
use Faker\Components\Config\Entity;
use Faker\PlatformFactory;

/**
 * This is a collection of database connections 
 * 
 * After using this project I found I had the need to connect to one or more staging database or information databases.
 * I also wanted to add a database writer to push statements instead of file writing. 
 * 
 * I also extended the config to allow many database configurations as this is the  best place for them 
 * instead of inside a xml or php schema files as these are included in source control while database config are often private.
 * 
 */  
class ConnectionPool 
{
   
   /**
    * @var array[Connection]
    */ 
   protected $otherConnections;
   
   /**
    * @var Faker\PlatformFactory
    */ 
   protected $platformFactory;
   
   
   public function __construct(PlatformFactory $platformFactory) 
   {
       $this->otherConnections = array();
       $this->platformFactory  = $platformFactory;
   }
   
   /**
    * Adds another connection from the database config file
    * 
    * @throws InvalidConfigException when connection name is empty or internal ref is given
    * @access public
    * @param string $name   the connection name
    * @param Entity $entity the config enitiy
    * @return Doctrine\DBAL\Connection
    */ 
   public function addExtraConnection($name,Entity $entity)
   {
       if($name === '__INTERNAL__') {
           throw new InvalidConfigException('not allowd to add a database connection using name __INTERNAL__');
       }
       
       if(true === empty($name)) {
           throw new InvalidConfigException('database connection name must not be empty');
       }
       
        $platform = $this->platformFactory;
         
        $connectionParams = array(
              'wrapperClass' => 'Faker\Components\Config\DoctrineConnWrapper',
              'dbname'      => $entity->getSchema(),
              'user'        => $entity->getUser(),
              'password'    => $entity->getPassword(),
              'host'        => $entity->getHost(),
              'driver'      => $entity->getType(),
              'platform'    => $platform->createFromDriver($entity->getType()),
        );
         
        if($entity->getUnixSocket() != false) {
            $connectionParams['unix_socket'] = $entity->getUnixSocket();
        }
         
        if($entity->getCharset() != false) {
            $connectionParams['charset']     = $entity->getCharset();
        }
         
        if($entity->getPath() != false) {
            $connectionParams['path']       = $entity->getPath();
        }
         
        if($entity->getMemory() != false) {
            $connectionParams['memory']     = $entity->getMemory();
        }
        
        if($entity->getPort() != false) {
            $connectionParams['port']     = $entity->getPort();
        }
         
        $connection        = DriverManager::getConnection($connectionParams, new Configuration());
       
        $this->otherConnections[$name] = $connection;
       
       return $connection;
   }
   
   /**
    * Sets the internal connection 
    * 
    * @access public
    * @return void
    * @param  Doctrine\DBAL\Connection $connect  the dbal connection already configured
    */ 
   public function setInternalConnection(Connection $connect)
   {
       if(isset($this->otherConnections['__INTERNAL__'])) {
            throw new InvalidConfigException('database at __INTERNAL__ already exists');
       }
       
       $this->otherConnections['__INTERNAL__'] =  $connect;
   }
   
   /**
    * Fetch faker internal database connection
    * 
    * @throws InvalidConfigException if the connnection does not exists
    * @access public
    * @return Doctrine\DBAL\Connection
    */ 
   public function fetchInternalConnection()
   {
       if(false === isset($this->otherConnections['__INTERNAL__'])) {
            throw new InvalidConfigException('database at __INTERNAL__ does not exists yet');
       }
       
       return $this->otherConnections['__INTERNAL__'];
   }
   
   
   /**
    * Fetch a connection from the pool
    * 
    * @throws InvalidConfigException if the connnection does not exists
    * @access public
    * @return Doctrine\DBAL\Connection
    * @param string $name the connection name
    */ 
   public function getExtraConnection($name)
   {
       if(false === $this->hasExtraConnection($name)) {
            throw new InvalidConfigException("database at '".$name."' does not exists yet");
       }
       
       return $this->otherConnections[$name];
   }
   
   
   /**
    *  check if a connection exists at x
    * 
    *  @param string    $name   The connection name
    *  @return boolean  true if connection exists
    */ 
   public function hasExtraConnection($name)
   {
       return isset($this->otherConnections[$name]);
   }
   
   
    
}
/* End of Class */

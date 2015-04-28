<?php
namespace Faker\Tests\Config;

use Faker\Project;
use Faker\Components\Config\Entity;
use Faker\Tests\Base\AbstractProject;
use Faker\Components\Config\ConnectionPool;

class ConnectionPoolTest extends AbstractProject
{
    
    public function testAddDefaultConnection()
    {
        $project = $this->getProject();
        $platform = $project['platform_factory'];
        $pool = new \Faker\Components\Config\ConnectionPool($platform);
        
        
        $mockConn = $this->getMockBuilder('Faker\\Components\\Config\\DoctrineConnWrapper')
             ->disableOriginalConstructor()
             ->getMock();
        
        $pool->setInternalConnection($mockConn);
        
        $this->assertEquals($mockConn,$pool->fetchInternalConnection());
    }
    
    /**
     * @expectedException Faker\Components\Config\InvalidConfigException
     * @expectedExceptionMessage database at __INTERNAL__ already exists
     */ 
    public function testErrorDefaultConnectionExists()
    {
        $project = $this->getProject();
        $platform = $project['platform_factory'];
        $pool = new \Faker\Components\Config\ConnectionPool($platform);
        
        
        $mockConn = $this->getMockBuilder('Faker\\Components\\Config\\DoctrineConnWrapper')
             ->disableOriginalConstructor()
             ->getMock();
        
        $pool->setInternalConnection($mockConn);
        $pool->setInternalConnection($mockConn);
        
    }
    
    public function testAddExtraConnection()
    {
        $project = $this->getProject();
        $platform = $project['platform_factory'];
        $pool = new \Faker\Components\Config\ConnectionPool($platform);
        $connectionName = 'MyTestConnection';
        
        $entity = new Entity();
        
        $entity->setCharset('latin1');
        $entity->setHost('localhost');
        $entity->setMemory(':memory');
        $entity->setPassword('vagrant');
        $entity->setPath('path/to/db/db.sqlite');
        $entity->setPort('3306');
        $entity->setSchema('sakila');
        $entity->setType('pdo_mysql');
        $entity->setUnixSocket('path/to/socker/socket.sock');
        $entity->setUser('root');
        $entity->setConnectionName($connectionName);

        $pool->addExtraConnection($connectionName,$entity);
        $conn = $pool->getExtraConnection($connectionName);
        
        # test properties
        $this->assertEquals('localhost',$conn->getHost());
        $this->assertEquals('vagrant',$conn->getPassword());
        $this->assertEquals('3306',$conn->getPort());
        $this->assertEquals('root',$conn->getUsername());
        
    }
}
/* End of Class */
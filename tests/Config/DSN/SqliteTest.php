<?php
namespace Faker\Tests\Config\DSN;

use Faker\Tests\Base\AbstractProject,
    Faker\Tests\Base\AbstractProjectWithDb,
    Faker\Components\Config\EntityInterface,
    Faker\Components\Config\Driver\DSN\Sqlite;

class SqliteTest extends AbstractProject
{
    
    
    public function testMergeNotMemory()
    {
        $parsed = array(
            'phptype'  => 'pdo_sqlite',
            'dbsyntax' => 'pdo_sqlite',
            'username' => 'root',
            'password' => 'vagrant',
            'protocol' => 'tcp',
            'hostspec' => 'localhost',
            'port'     => 3306,
            'socket'   => false,
            'database' => 'mydb.sqlite',
        );
        
        
        
        $entity = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setUser')->with($this->equalTo('root'));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_sqlite'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo('vagrant'));
        $entity->expects($this->once())->method('setPath')->with($this->equalTo('mydb.sqlite'));
        $entity->expects($this->once())->method('setMemory')->with($this->equalTo(false));
        
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    
    }
    
    public function testMergeMemory()
    {
        $parsed = array(
            'phptype'  => 'pdo_sqlite',
            'dbsyntax' => 'pdo_sqlite',
            'username' => 'root',
            'password' => 'vagrant',
            'protocol' => 'tcp',
            'hostspec' => 'localhost',
            'port'     => 3306,
            'socket'   => false,
            'database' => false,
            'memory'   => ':memory'
        );
        
        
        
        $entity = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setUser')->with($this->equalTo('root'));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_sqlite'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo('vagrant'));
        $entity->expects($this->once())->method('setPath')->with($this->equalTo(false));
        $entity->expects($this->once())->method('setMemory')->with($this->equalTo(':memory'));
        
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    
    }
    
    public function testMergeOptionalUserAndPassword()
    {
        $parsed = array(
            'phptype'  => 'pdo_sqlite',
            'dbsyntax' => 'pdo_sqlite',
            'username' => false,
            'password' => false,
            'protocol' => 'tcp',
            'hostspec' => 'localhost',
            'port'     => 3306,
            'socket'   => false,
            'database' => false,
            'memory'   => ':memory'
        );
        
        
        
        $entity = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setUser')->with($this->equalTo(false));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_sqlite'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo(false));
        $entity->expects($this->once())->method('setPath')->with($this->equalTo(false));
        $entity->expects($this->once())->method('setMemory')->with($this->equalTo(':memory'));
        
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    
    }
    
    /**
      *  @expectedException \Faker\Components\Config\InvalidConfigException
      *  @expectedExceptionMessage Invalid configuration for path "database.phptype": Database is not a valid type
      */
    public function testParseInvalidTypeConfig()
    {
        # unsupported db typ
        $parsed = array(
            'phptype'  => 'mysql',
            'dbsyntax' => 'mysql',
            'username' => 'root',
            'password' => 'vagrant',
            'protocol' => 'tcp',
            'hostspec' => 'localhost',
            'port'     => 3306,
            'socket'   => false,
            'database' => 'sakila',
        );
        
        
        
        $entity = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
        $dsn = new Sqlite();
        $dsn->merge($entity,$parsed);
    }
    
    
}
/* End of File MysqlTest.php */

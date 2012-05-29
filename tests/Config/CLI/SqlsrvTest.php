<?php
namespace Faker\Tests\Config\CLI;

use Faker\Tests\Base\AbstractProject,
    Faker\Tests\Base\AbstractProjectWithDb,
    Faker\Components\Config\EntityInterface,
    Faker\Components\Config\Driver\CLI\Sqlsrv;

class SqlsrvTest extends AbstractProject
{
    
    
    public function testMergeGoodConfig()
    {
        $parsed = array(
            'type'  => 'pdo_sqlsrv',
            'username' => 'root',
            'password' => 'vagrant',
            'host' => 'localhost',
            'port'     => 3306,
            'schema' => 'sakila',
        );
        
        
        
        $entity = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setSchema')->with($this->equalTo('sakila'));
        $entity->expects($this->once())->method('setUser')->with($this->equalTo('root'));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_sqlsrv'));
        $entity->expects($this->once())->method('setPort')->with($this->equalTo(3306));
        $entity->expects($this->once())->method('setHost')->with($this->equalTo('localhost'));
        $entity->expects($this->once())->method('setPassword')->with($this->equalTo('vagrant'));
        
        $dsn = new Sqlsrv();
        $dsn->merge($entity,$parsed);
    
    }
    
    /**
      *  @expectedException \Faker\Components\Config\InvalidConfigException
      *  @expectedExceptionMessage Invalid configuration for path "database.type": Database is not a valid type
      */
    public function testParseInvalidTypeConfig()
    {
        # unsupported db typ
        $parsed = array(
            'type'  => 'pdo_mysql',
            'username' => 'root',
            'password' => 'vagrant',
            'host' => 'localhost',
            'port'     => 3306,
            'schema' => 'sakila',
        );
        
        $entity = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
        $dsn = new Sqlsrv();
        $dsn->merge($entity,$parsed);
    }
    
    
    
}
/* End of File MysqlTest.php */

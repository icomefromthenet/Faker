<?php
namespace Faker\Tests\Config\DSN;

use Faker\Tests\Base\AbstractProject,
    Faker\Tests\Base\AbstractProjectWithDb,
    Faker\Components\Config\EntityInterface,
    Faker\Components\Config\Driver\DSN\Pgsql;

class PgSqlTest extends AbstractProject
{
    
    
    public function testMergeGoodConfig()
    {
        $parsed = array(
            'phptype'  => 'pdo_pgsql',
            'dbsyntax' => 'pdo_pgsql',
            'username' => 'root',
            'password' => 'vagrant',
            'protocol' => 'tcp',
            'hostspec' => 'localhost',
            'port'     => 3306,
            'socket'   => false,
            'database' => 'sakila',
        );
        
        
        
        $entity = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
    
        $entity->expects($this->once())->method('setSchema')->with($this->equalTo('sakila'));
        $entity->expects($this->once())->method('setUser')->with($this->equalTo('root'));
        $entity->expects($this->once())->method('setType')->with($this->equalTo('pdo_pgsql'));
        $entity->expects($this->once())->method('setPort')->with($this->equalTo(3306));
        $entity->expects($this->once())->method('setHost')->with($this->equalTo('localhost'));
        
        $dsn = new Pgsql();
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
            'phptype'  => 'pdo_mysql',
            'dbsyntax' => 'pdo_mysql',
            'username' => 'root',
            'password' => 'vagrant',
            'protocol' => 'tcp',
            'hostspec' => 'localhost',
            'port'     => 3306,
            'socket'   => false,
            'database' => 'sakila',
        );
        
        
        
        $entity = $this->getMockBuilder('\Faker\Components\Config\EntityInterface')->getMock();
        $dsn = new Pgsql();
        $dsn->merge($entity,$parsed);
    }
    
    
}
/* End of File MysqlTest.php */

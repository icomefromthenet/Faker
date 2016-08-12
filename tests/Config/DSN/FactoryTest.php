<?php
namespace Faker\Tests\Config\DSN;

use Faker\Components\Config\Driver\DsnFactory,
    Faker\Tests\Base\AbstractProject;

class FactoryTest extends AbstractProject
{

    public function testFactoryCreate()
    {
        $factory = new DsnFactory();
        
        $this->assertInstanceOf('Faker\Components\Config\Driver\DSN\Mysql',$factory->create('pdo_mysql'));
        $this->assertInstanceOf('Faker\Components\Config\Driver\DSN\Sqlite',$factory->create('pdo_sqlite'));
        $this->assertInstanceOf('Faker\Components\Config\Driver\DSN\Sqlsrv',$factory->create('pdo_sqlsrv'));
        $this->assertInstanceOf('Faker\Components\Config\Driver\DSN\Pgsql',$factory->create('pdo_pgsql'));
        $this->assertInstanceOf('Faker\Components\Config\Driver\DSN\Oci',$factory->create('pdo_oci'));
        $this->assertInstanceOf('Faker\Components\Config\Driver\DSN\Oci',$factory->create('oci8'));
    }

    
    /**
      *  @expectedException Faker\Components\Config\Exception
      *  @expectedExceptionMessage DSN Driver not found at badkey
      */
    public function testFactoryCreateBadKey()
    {
        $factory = new DsnFactory();
        $factory->create('badkey');
    }
    
    
    public function testFactoryUpperCaseKeyOk()
    {
        
        $factory = new DsnFactory();
        $this->assertInstanceOf('Faker\Components\Config\Driver\DSN\Mysql',$factory->create('PDO_MYSQL'));
        $this->assertInstanceOf('Faker\Components\Config\Driver\DSN\Sqlite',$factory->create('PDO_SQLITE'));
        $this->assertInstanceOf('Faker\Components\Config\Driver\DSN\Sqlsrv',$factory->create('PDO_SQLSRV'));
        $this->assertInstanceOf('Faker\Components\Config\Driver\DSN\Pgsql',$factory->create('PDO_PGSQL'));
        $this->assertInstanceOf('Faker\Components\Config\Driver\DSN\Oci',$factory->create('PDO_OCI'));
        $this->assertInstanceOf('Faker\Components\Config\Driver\DSN\Oci',$factory->create('OCI8'));
        
        
    }

}
/* End of File */
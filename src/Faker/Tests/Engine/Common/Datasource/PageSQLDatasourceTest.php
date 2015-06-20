<?php
namespace Faker\Tests\Engine\Common\Datasource;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Datasource\PageSQLDatasource;
use Faker\Components\Engine\EngineException;
use Faker\Components\Config\ConnectionPool;
use Faker\Components\Config\Entity;
use Faker\PlatformFactory;

class SQLDatasourceTest extends AbstractProject
{
 
    protected function getMockConnection($uniqueConnectionNameA)
    {
        
        $platformFactory = new PlatformFactory();
        $pool            = new ConnectionPool($platformFactory);
        
        $connectionA   = new Entity();
      
        $connectionA->setType('pdo_sqlite');
        $connectionA->setMemory(true);
        $dbConnect = $pool->addExtraConnection($uniqueConnectionNameA,$connectionA);
        
        # setup a schema
        $dbConnect->exec(' CREATE TABLE tbltest(ID INT PRIMARY KEY NOT NULL);');
        
        # insert some data
        $dbConnect->exec('INSERT INTO tbltest (ID) VALUES (1)');
        $dbConnect->exec('INSERT INTO tbltest (ID) VALUES (2)');
        $dbConnect->exec('INSERT INTO tbltest (ID) VALUES (3)');
        $dbConnect->exec('INSERT INTO tbltest (ID) VALUES (4)');
        $dbConnect->exec('INSERT INTO tbltest (ID) VALUES (5)');
        $dbConnect->exec('INSERT INTO tbltest (ID) VALUES (6)');
        $dbConnect->exec('INSERT INTO tbltest (ID) VALUES (7)');
        $dbConnect->exec('INSERT INTO tbltest (ID) VALUES (8)');
        
        
        return $dbConnect;
        
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage The child node "query" at path "config" must be configured
     * 
     */ 
    public function testQueryParmRequired()
    {
        # create datasource and assign basic param
        $mock = new PageSQLDatasource();
        $mock->setOption('id','MySoruceA');
        $mock->setOption('connection','connectA');
        $mock->setOption('limit',1);
        
        # validate the source
        $mock->validate();
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage The value 0 is too small for path "config.limit". Should be greater than or equal to 1
     */ 
    public function testLimitCannotBeLessThenONE()
    {
        # create datasource and assign basic param
        $mock = new PageSQLDatasource();
        $mock->setOption('id','MySoruceA');
        
        $mock->setOption('query','SELECT * FROM tbltest');
        $mock->setOption('connection','connectA');
        $mock->setOption('limit',0);
        
        
        # validate the source
        $mock->validate();
        
    }
 
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage The value -1 is too small for path "config.offset". Should be greater than or equal to 0
     */ 
    public function testOffsetCannotBeLessThanZero()
    {
        # create datasource and assign basic param
        $mock = new PageSQLDatasource();
        $mock->setOption('id','MySoruceA');
        
        $mock->setOption('query','SELECT * FROM tbltest');
        $mock->setOption('connection','connectA');
        $mock->setOption('limit',1);
        $mock->setOption('offset',-1);
        
        
        # validate the source
        $mock->validate();
        
    }
    
     /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage The path "config.connection" cannot contain an empty value
     */
    public function testConnectionNameCanBeNotEmpty()
    {
        # create datasource and assign basic param
        $mock = new PageSQLDatasource();
        $mock->setOption('id','MySoruceA');
        
        $mock->setOption('query','SELECT * FROM tbltest');
        $mock->setOption('connection','');
        $mock->setOption('limit',1);
        $mock->setOption('offset',0);
        
        
        # validate the source
        $mock->validate();
        
    }
    
    
    public function testDatasourceSingleMode()
    {
        # setup a database to reference    
        $uniqueConnectionNameA = 'connection.a';
        $dbConnect = $this->getMockConnection($uniqueConnectionNameA);
        
        # create datasource and assign basic param
        $mock = new PageSQLDatasource();
        $mock->setOption('id','MySoruceA');
        $mock->setOption('connection',$uniqueConnectionNameA);
        $mock->setOption('limit',1);
        $mock->setOption('offset',0);
        
        # assign the query params
        $mock->setOption('query','SELECT * FROM tbltest');
        
        $mock->setExtraConnection($dbConnect);
        
        # validate the source
        $mock->validate();
       
        # no error thrown 
        $mock->initSource();    
      
        $this->assertEquals(array('ID'=>1),$mock->fetchOne());
        $this->assertEquals(array('ID'=>2),$mock->fetchOne());
        $this->assertEquals(array('ID'=>3),$mock->fetchOne());
        $this->assertEquals(array('ID'=>4),$mock->fetchOne());
        $this->assertEquals(array('ID'=>5),$mock->fetchOne());
        $this->assertEquals(array('ID'=>6),$mock->fetchOne());
        $this->assertEquals(array('ID'=>7),$mock->fetchOne());
        $this->assertEquals(array('ID'=>8),$mock->fetchOne());
        
        $mock->flushSource();
        $mock->initSource();    
         
        $this->assertEquals(array('ID'=>1),$mock->fetchOne());
        $this->assertEquals(array('ID'=>2),$mock->fetchOne());
        $this->assertEquals(array('ID'=>3),$mock->fetchOne());
        $this->assertEquals(array('ID'=>4),$mock->fetchOne());
        $this->assertEquals(array('ID'=>5),$mock->fetchOne());
        $this->assertEquals(array('ID'=>6),$mock->fetchOne());
        $this->assertEquals(array('ID'=>7),$mock->fetchOne());
        $this->assertEquals(array('ID'=>8),$mock->fetchOne());
        
        
        $mock->cleanupSource();
        
    }
    
    public function testDatasourceBulkMode()
    {
        # setup a database to reference    
        $uniqueConnectionNameA = 'connection.a';
        $dbConnect = $this->getMockConnection($uniqueConnectionNameA);
        
        # create datasource and assign basic param
        $mock = new PageSQLDatasource();
        $mock->setOption('id','MySoruceA');
        $mock->setOption('connection',$uniqueConnectionNameA);
        $mock->setOption('limit',5);
        $mock->setOption('offset',0);
        
        # assign the query params
        $mock->setOption('query','SELECT * FROM tbltest');
        
        $mock->setExtraConnection($dbConnect);
        
        # validate the source
        $mock->validate();
       
        # no error thrown 
        $mock->initSource();    
      
        $this->assertEquals($mock->fetchOne(),array('ID'=>1));
        $this->assertEquals($mock->fetchOne(),array('ID'=>2));
        $this->assertEquals($mock->fetchOne(),array('ID'=>3));
        $this->assertEquals($mock->fetchOne(),array('ID'=>4));
        $this->assertEquals($mock->fetchOne(),array('ID'=>5));
        
        $this->assertEquals($mock->fetchOne(),array('ID'=>6));
        $this->assertEquals($mock->fetchOne(),array('ID'=>7));
        $this->assertEquals($mock->fetchOne(),array('ID'=>8));
        
        $mock->flushSource();
        $mock->initSource();    
         
        $this->assertEquals($mock->fetchOne(),array('ID'=>1));
        $this->assertEquals($mock->fetchOne(),array('ID'=>2));
        $this->assertEquals($mock->fetchOne(),array('ID'=>3));
        $this->assertEquals($mock->fetchOne(),array('ID'=>4));
        $this->assertEquals($mock->fetchOne(),array('ID'=>5));
      
        $this->assertEquals($mock->fetchOne(),array('ID'=>6));
        $this->assertEquals($mock->fetchOne(),array('ID'=>7));
        $this->assertEquals($mock->fetchOne(),array('ID'=>8));
        
        $mock->cleanupSource();
        
        
        
    }
    
    
}
/* End of File */
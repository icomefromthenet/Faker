<?php
namespace Faker\Tests\Engine\Common\Datasource;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Datasource\SimpleSQLDatasource;
use Faker\Components\Engine\EngineException;
use Faker\Components\Config\ConnectionPool;
use Faker\Components\Config\Entity;
use Faker\PlatformFactory;

class SingleSQLDatasourceTest extends AbstractProject
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
        $mock = new SimpleSQLDatasource();
        $mock->setOption('id','MySoruceA');
        $mock->setOption('connection','connectA');
        
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
        $mock = new SimpleSQLDatasource();
        $mock->setOption('id','MySoruceA');
        
        $mock->setOption('query','SELECT * FROM tbltest ORDER BY RANDOM() LIMIT 1');
        $mock->setOption('connection','');
        
        
        # validate the source
        $mock->validate();
        
    }
    
    
    public function testDatasourceSingleMode()
    {
        # setup a database to reference    
        $uniqueConnectionNameA = 'connection.a';
        $dbConnect = $this->getMockConnection($uniqueConnectionNameA);
        
        # create datasource and assign basic param
        $mock = new SimpleSQLDatasource();
        $mock->setOption('id','MySoruceA');
        $mock->setOption('connection',$uniqueConnectionNameA);
        
        # assign the query params
        $mock->setOption('query','SELECT * FROM tbltest');
        
        $mock->setExtraConnection($dbConnect);
        
        # validate the source
        $mock->validate();
       
        # no error thrown 
        $mock->initSource();    
      
        $aValue = $mock->fetchOne();
        $this->assertThat($aValue['ID'],$this->logicalAnd($this->greaterThan(0),$this->lessThan(10)));
        
        $aValue = $mock->fetchOne(); 
        $this->assertThat($aValue['ID'],$this->logicalAnd($this->greaterThan(0),$this->lessThan(10)));
        
        $aValue = $mock->fetchOne(); 
        $this->assertThat($aValue['ID'],$this->logicalAnd($this->greaterThan(0),$this->lessThan(10)));
        
        $aValue = $mock->fetchOne(); 
        $this->assertThat($aValue['ID'],$this->logicalAnd($this->greaterThan(0),$this->lessThan(10)));
      
        
     
        $mock->flushSource();
        $mock->initSource();    
         
        $aValue = $mock->fetchOne();
        $this->assertThat($aValue['ID'],$this->logicalAnd($this->greaterThan(0),$this->lessThan(10)));
        
        $aValue = $mock->fetchOne(); 
        $this->assertThat($aValue['ID'],$this->logicalAnd($this->greaterThan(0),$this->lessThan(10)));
        
        $aValue = $mock->fetchOne(); 
        $this->assertThat($aValue['ID'],$this->logicalAnd($this->greaterThan(0),$this->lessThan(10)));
        
        $aValue = $mock->fetchOne(); 
        $this->assertThat($aValue['ID'],$this->logicalAnd($this->greaterThan(0),$this->lessThan(10)));
      
      
        $mock->cleanupSource();
    }
    
}
/* End of File */
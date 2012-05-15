<?php
namespace Faker\Tests\Faker;

use Faker\Tests\Base\AbstractProject;

class SqliteDbTest extends AbstractProject
{
    
    public function testDIConnectionSetup()
    {
        
        $project = $this->getProject(); 
        
        $connection = $project['faker_database'];
        $this->assertInstanceOf('\Doctrine\DBAL\Connection',$connection);
        
        return $connection;
        
        
    }
    
    /**
      * @depends testDIConnectionSetup  
      */
    public function testInstallScript(\Doctrine\DBAL\Connection $connection)
    {
        $schema = $connection->getSchemaManager()->createSchema();
    }
    
    
}
/* End of File */
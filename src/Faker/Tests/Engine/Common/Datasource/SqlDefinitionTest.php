<?php
namespace Faker\Tests\Engine\Common\Datasource;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Datasource\SimpleSQLDefinition;
use Faker\Components\Engine\Common\Datasource\BulkSQLDefinition;
use Faker\Components\Engine\Common\Datasource\PageSQLDefinition;

class SimpleSQLDefinitionTest extends AbstractProject
{
    
    public function testImplementsCorrectInterfaces()
    {
        $def = new SimpleSQLDefinition();
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Builder\\TypeDefinitionInterface',$def);
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Builder\\NodeInterface',$def);
    }
    
    
    public function testSimpleDefinition()
    {
       $def = new SimpleSQLDefinition();
       
        $sDataSourceName = 'mysourceA';
        $sConnectionName = 'connectionNameA';
        $sQuery          = 'Select * FROM DUAL LIMIT 1';
        
        $this->assertEquals($def,$def->setDatasourceName($sDataSourceName));
        $this->assertEquals($def,$def->setConnectionName($sConnectionName));
        $this->assertEquals($def,$def->setQuery($sQuery));
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Datasource\\SimpleSQLDatasource',$def->getNode());
        
    }
    
    public function testBulkDefinition()
    {
       $def = new BulkSQLDefinition();
       
        $sDataSourceName = 'mysourceA';
        $sConnectionName = 'connectionNameA';
        $sQuery          = 'Select * FROM DUAL LIMIT 1';
        $iLimit          = 1;
        $iOffset         = 5;
        
        $this->assertEquals($def,$def->setDatasourceName($sDataSourceName));
        $this->assertEquals($def,$def->setConnectionName($sConnectionName));
        $this->assertEquals($def,$def->setQuery($sQuery));
        $this->assertEquals($def,$def->setLimit($iLimit));
        $this->assertEquals($def,$def->setOffset($iOffset));
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Datasource\\BulkSQLDatasource',$def->getNode());
        
    }
    
    public function testPageDefinition()
    {
       $def = new PageSQLDefinition();
       
        $sDataSourceName = 'mysourceA';
        $sConnectionName = 'connectionNameA';
        $sQuery          = 'Select * FROM DUAL LIMIT 1';
        $iLimit          = 1;
        $iOffset         = 5;
        
        $this->assertEquals($def,$def->setDatasourceName($sDataSourceName));
        $this->assertEquals($def,$def->setConnectionName($sConnectionName));
        $this->assertEquals($def,$def->setQuery($sQuery));
        $this->assertEquals($def,$def->setLimit($iLimit));
        $this->assertEquals($def,$def->setOffset($iOffset));
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Datasource\\PageSQLDatasource',$def->getNode());
        
    }
    
}
/* End of File */
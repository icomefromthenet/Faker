<?php
namespace Faker\Tests\Engine\Common\Datasource;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Datasource\DatasourceRepository;

class DatasourceRepoTest extends AbstractProject
{
    
    public function testExtensionRegister()
    {
       DatasourceRepository::registerExtension('example_source','Faker\Tests\Engine\Common\Datasource\ExampleDatasource');
        
       $repo = new DatasourceRepository();
       
       $this->assertEquals($repo->find('example_source'),'Faker\Tests\Engine\Common\Datasource\ExampleDatasource');
    }
    
    public function testExtensionClear()
    {
       DatasourceRepository::registerExtension('example_source','Faker\Tests\Engine\Common\Datasource\ExampleDatasource');
       DatasourceRepository::clearExtensions();
       
       $repo = new DatasourceRepository();
       
       $this->assertEquals($repo->find('example_source'),null);
    }
    
}
/* End of File */
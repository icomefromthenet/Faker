<?php
namespace Faker\Tests\Engine\Common\Datasource;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Datasource\PHPDatasource;
use Faker\Components\Engine\EngineException;

class PHPDatasourceTest extends AbstractProject
{
    
    public function testDatasourceInterfaceImplementation()
    {
        
        $iterator = new \ArrayIterator();
        $iterator->append(array('value'=> 1));
        $iterator->append(array('value'=> 2));
        $iterator->append(array('value'=> 3));
        $iterator->append(array('value'=> 4));
        $iterator->append(array('value'=> 5));
        $iterator->append(array('value'=> 6));
        
        $mock = new PHPDatasource();
        $mock->setIterator($iterator);
        $mock->setOption('name','unique_source_1');
       
        $mock->validate();
        
       
        # no error thrown as we have an iterator
        $mock->initSource();    
        
        $this->assertEquals($mock->fetchOne(),array('value'=>1));
        $this->assertEquals($mock->fetchOne(),array('value'=>1));
        
        $mock->flushSource();
        
        $this->assertEquals($mock->fetchOne(),array('value'=>2));
        
        $mock->flushSource();
        
        $this->assertEquals($mock->fetchOne(),array('value'=>3));
        
        $mock->cleanupSource();
        
        $this->assertEquals($mock->fetchOne(),array('value'=>1));
        
    }
    
    /**
    * @expectedException Faker\Components\Engine\EngineException
    * @expectedExceptionMessage PHPDatasource must have some data assigned
    */ 
    public function testDatasourceValidateFailsEmptyData()
    {
        $mock = new PHPDatasource();
        $mock->setOption('name','unique_source_1');
        $mock->validate();
        
    }
    
    
}
/* End of File */
<?php
namespace Faker\Tests\Doctrine;

use Faker\ColumnTypeFactory,
    Faker\Tests\Base\AbstractProject;

class ColumnTypeFactoryTest extends AbstractProject
{

    public function testFactoryCreate()
    {
        $factory = new ColumnTypeFactory();
        $project = $this->getProject();
        
        $this->assertInstanceOf('Doctrine\DBAL\Types\ArrayType',$factory->create('array'));
        
    }

    
    /**
      *  @expectedException Faker\Components\Faker\Exception 
      */
    public function testFactoryCreateBadKey()
    {
        $factory = new ColumnTypeFactory();
        $factory->create('badkey');
    }
    
    /**
      *  @expectedException Faker\Components\Faker\Exception 
      */
    public function testregisterExtension()
    {
        ColumnTypeFactory::registerExtension('mytype','Faker\\Components\\Extension\\Doctine\\Type\\MyType');
        $factory = new ColumnTypeFactory();
        $this->assertTrue(true);
        
        $factory->create('mytype');
    }
    
    /**
      *  @expectedException Faker\Components\Faker\Exception 
      */
    public function testregisterManyExtension()
    {
        
        ColumnTypeFactory::registerExtensions(array('mytype','Faker\\Components\\Extension\\Doctine\\Type\\MyType'));
        $factory = new ColumnTypeFactory();
        $this->assertTrue(true);
        
        $factory->create('mytype');
        
    }
    
    
    
    
}
/* End of File */
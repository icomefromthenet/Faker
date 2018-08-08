<?php
namespace Faker\Tests\Engine\Common\Composite;

use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Composite\DatasourceNode;
use Faker\Tests\Base\AbstractProject;

class TypeCompositeTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        $id        = 'testnode';
        
        $internal  = $this->createMock('\Faker\Components\Engine\Common\Type\TypeInterface');
            
        $type = new TypeNode($id,$event,$internal);
        $type->setParent($parent);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\CompositeInterface',$type);
        
    }
    
    
    public function testTypeInterfaceProperties()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        $id        = 'testnode';
        
        $internal  = $this->createMock('\Faker\Components\Engine\Common\Type\TypeInterface');
        
            
        $type = new TypeNode($id,$event,$internal);
        $type->setParent($parent);
        
        
        $this->assertEquals($parent,$type->getParent());
        
    }
    
    
    public function testCompositeProperties()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        $parentB   = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        
        $id        = 'testnode';
        
        $internal  = $this->createMock('\Faker\Components\Engine\Common\Type\TypeInterface');
        
        $type = new TypeNode($id,$event,$internal);
        $type->setParent($parent);
        
        $this->assertEquals($id,$type->getId());        
        $this->assertEquals($event,$type->getEventDispatcher());
        $this->assertEquals($parent,$type->getParent());
        
        $type->setParent($parentB);
        $this->assertEquals($parentB,$type->getParent());
        $this->assertEquals(array(),$type->getChildren());
        
        $type->addChild($parentB);
        
        $typeChildren = $type->getChildren();
        
        $this->assertEquals($parentB,$typeChildren[0]);
        
    }
    
    public function checkValidateCalledOnInternal()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        $id        = 'testnode';
        
        $internal  = $this->createMock('\Faker\Components\Engine\Common\Type\TypeInterface');
        $internal->expects($this->once())
               ->method('validate'); 
            
        $type = new TypeNode($id,$event,$internal);
        $type->setParent($parent);
        
        $this->assertTrue($type->validate());
        
    }
    
    
    public function testGenerate()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        
        $event     = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        
        $parent    = $this->createMock('Faker\Components\Engine\Common\Composite\CompositeInterface');

        $internal  = $this->createMock('\Faker\Components\Engine\Common\Type\TypeInterface');
        $internal->expects($this->once())
               ->method('generate')
               ->with($this->equalTo(5),$this->equalTo(array('row1' => 6)))
               ->will($this->returnValue('a generated string'));
        
        
        $id        = 'testnode';

        $type = new TypeNode($id,$event,$internal);
        $type->setParent($parent);
        
        $values = array('row1' => 6);
        $this->assertEquals('a generated string',$type->generate(5,$values));
        
    }
    
    
    public function testFetchDataFromSources()
    {
        
        $dNode        = $this->createMock('Faker\Components\Engine\Common\Datasource\DatasourceInterface');
        
        $event        = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $dataNodeID   = 'DatasourceNodeA';   
        
        $datasourceNode =    new DatasourceNode($dataNodeID,$event, $dNode);
             
        $dNode->expects($this->once())
              ->method('fetchOne'); 
              
              
        $internal  = $this->createMock('\Faker\Components\Engine\Common\Type\TypeInterface');
        $internal->expects($this->once())
               ->method('generate');
               
        $id        = 'testnode';

        $type = new TypeNode($id,$event,$internal);
        
        $type->addChild($datasourceNode);
        
        $values = array();
        $type->generate(1,$values);
    }
    
}
/* End of File */
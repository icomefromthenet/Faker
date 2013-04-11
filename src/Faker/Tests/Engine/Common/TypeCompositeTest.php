<?php
namespace Faker\Tests\Engine\Common;

use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Tests\Base\AbstractProject;

class TypeCompositeTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
        
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        $id        = 'testnode';
        
        $internal  = $this->getMock('\Faker\Components\Engine\Common\Type\TypeInterface');
            
        $type = new TypeNode($id,$event,$internal);
        $type->setParent($parent);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\CompositeInterface',$type);
        
    }
    
    
    public function testTypeInterfaceProperties()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
        
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        $id        = 'testnode';
        
        $internal  = $this->getMock('\Faker\Components\Engine\Common\Type\TypeInterface');
        
            
        $type = new TypeNode($id,$event,$internal);
        $type->setParent($parent);
        
        
        $this->assertEquals($parent,$type->getParent());
        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage TypeNode can not have children
      */
    public function testCompositeProperties()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
        
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        $parentB   = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        
        $id        = 'testnode';
        
        $internal  = $this->getMock('\Faker\Components\Engine\Common\Type\TypeInterface');
        
        $type = new TypeNode($id,$event,$internal);
        $type->setParent($parent);
        
        $this->assertEquals($id,$type->getId());        
        $this->assertEquals($event,$type->getEventDispatcher());
        $this->assertEquals($parent,$type->getParent());
        
        $type->setParent($parentB);
        $this->assertEquals($parentB,$type->getParent());
        $this->assertEquals(array(),$type->getChildren());
        
        $type->addChild($parentB);
        
    }
    
    public function checkValidateCalledOnInternal()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
        
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        $id        = 'testnode';
        
        $internal  = $this->getMock('\Faker\Components\Engine\Common\Type\TypeInterface');
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
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
        
        $event     = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        
        $parent    = $this->getMock('Faker\Components\Engine\Common\Composite\CompositeInterface');

        $internal  = $this->getMock('\Faker\Components\Engine\Common\Type\TypeInterface');
        $internal->expects($this->once())
               ->method('generate')
               ->with($this->equalTo(5),$this->equalTo(array('row1' => 6)))
               ->will($this->returnValue('a generated string'));
        
        
        $id        = 'testnode';

        $type = new TypeNode($id,$event,$internal);
        $type->setParent($parent);
        
        $this->assertEquals('a generated string',$type->generate(5,array('row1' => 6)));
        
    }
    
}
/* End of File */
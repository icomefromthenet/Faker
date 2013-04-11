<?php
namespace Faker\Tests\Engine\Common;

use Faker\Components\Engine\Common\Composite\SelectorNode;
use Faker\Tests\Base\AbstractProject;

class SelectorCompositeTest extends AbstractProject
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
            
        $type = new SelectorNode($id,$event,$internal);
        $type->setParent($parent);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\CompositeInterface',$type);
        
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
        
        $type = new SelectorNode($id,$event,$internal);
        $type->setParent($parent);
        
        $this->assertEquals($parent,$type->getParent());
        
    }
    
    
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
        
        $childA    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        $childB    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        $id        = 'testnode';
        
        $internal  = $this->getMock('\Faker\Components\Engine\Common\Type\TypeInterface');
        
        $type = new SelectorNode($id,$event,$internal);
        $type->setParent($parent);
        
        $this->assertEquals($id,$type->getId());        
        $this->assertEquals($event,$type->getEventDispatcher());
        $this->assertEquals($parent,$type->getParent());
        
        $type->setParent($parentB);
        $this->assertEquals($parentB,$type->getParent());
        
        $type->addChild($childA);
        $type->addChild($childB);
        
        $this->assertEquals(array($childA,$childB),$type->getChildren());
        
    }
    
    public function checkValidateCalledOnChildren()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
        
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        $childA    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        $childB    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        $childA->expects($this->once())
               ->method('validate'); 
        
        $childB->expects($this->once())
               ->method('validate'); 
        
        $id        = 'testnode';
        
        $internal  = $this->getMock('\Faker\Components\Engine\Common\Type\TypeInterface');
        
        $internal->expects($this->once())
               ->method('validate'); 
            
        $type = new SelectorNode($id,$event,$internal);
        $type->setParent($parent);
        
        
        $type->addChild($childA);
        $type->addChild($childB);
        
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
        
        $childA    = $this->getMock('Faker\Components\Engine\Common\Composite\CompositeInterface');
        
        $childA->expects($this->once())
               ->method('generate')
               ->with($this->equalTo(5),$this->equalTo(array('row1' => 6)))
               ->will($this->returnValue('a generated string'));
        
        $childB    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        $id        = 'testnode';
        
        $internal  = $this->getMock('\Faker\Components\Engine\Common\Type\TypeInterface');
        
        $internal->expects($this->once())
               ->method('generate')
               ->with($this->equalTo(5),$this->equalTo(array('row1' => 6)))
               ->will($this->returnValue(1));
            
        $type = new SelectorNode($id,$event,$internal);
        $type->setParent($parent);

        $type->addChild($childA);
        $type->addChild($childB);
        
        $this->assertEquals('a generated string',$type->generate(5,array('row1' => 6)));
        
        
    }
    
}
/* End of File */
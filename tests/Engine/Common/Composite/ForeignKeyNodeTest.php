<?php
namespace Faker\Tests\Engine\Common\Composite;

use Faker\Components\Engine\Common\Composite\ForeignKeyNode;
use Faker\Tests\Base\AbstractProject;

/**
  *  Test the node implementes CompositeInterface correctly
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ForeignKeyNodeTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        $id        = 'ForeignKeyNode';
        
        $internal  = $this->createMock('\Faker\Components\Engine\Common\Type\TypeInterface');
            
        $type = new ForeignKeyNode($id,$event,$internal);
        $type->setParent($parent);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\CompositeInterface',$type);
        
    }
    
    
    public function testCompositeProperties()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        $parentB   = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        $id        = 'ForeignKeyNode';
        
        
        $type = new ForeignKeyNode($id,$event);
        $type->setParent($parent);
        $type->setUseCache(false);
        
        $this->assertEquals($id,$type->getId());        
        $this->assertEquals($event,$type->getEventDispatcher());
        $this->assertEquals($parent,$type->getParent());
        $this->assertEquals(false,$type->getUseCache());
        
        $type->setUseCache(true);
        $type->setParent($parentB);
        $this->assertEquals($parentB,$type->getParent());
        $this->assertEquals(array(),$type->getChildren());
        $this->assertEquals(true,$type->getUseCache());
        
        $type->addChild($parentB);
        
    }
    
    
    public function testValidateWithChildren()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        $id        = 'ForeignKeyNode';
        $child   = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        $child->expects($this->once())
              ->method('validate');  
        
        $type = new ForeignKeyNode($id,$event);
        $type->setParent($parent);
        $type->addChild($child);
        
        $type->validate();
    }
    
    
    /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage ForeignKey must have a name
      */
    public function testValidateFailsWithEmptyId()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        $id        = null;
        
        $type = new ForeignKeyNode($id,$event);
        $type->setParent($parent);
        
        $type->validate();
        
    }
    
}
/* End of File */
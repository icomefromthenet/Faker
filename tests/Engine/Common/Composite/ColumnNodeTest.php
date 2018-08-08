<?php
namespace Faker\Tests\Engine\Common\Composite;

use Faker\Components\Engine\Common\Composite\ColumnNode;
use Faker\Tests\Base\AbstractProject;

/**
  *  Test the node implementes CompositeInterface correctly
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ColumnNodeTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        $id        = 'columnNode';
        
        $internal  = $this->createMock('\Faker\Components\Engine\Common\Type\TypeInterface');
            
        $type = new ColumnNode($id,$event,$internal);
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
        $id        = 'columnNode';
        
        
        $type = new ColumnNode($id,$event);
        $type->setParent($parent);
        
        $this->assertEquals($id,$type->getId());        
        $this->assertEquals($event,$type->getEventDispatcher());
        $this->assertEquals($parent,$type->getParent());
        
        $type->setParent($parentB);
        $this->assertEquals($parentB,$type->getParent());
        $this->assertEquals(array(),$type->getChildren());
        
        $type->addChild($parentB);
        
    }
    
    
    public function testValidateWithChildren()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        $id        = 'columnNode';
        $child   = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        $child->expects($this->once())
              ->method('validate');  
        
        $type = new ColumnNode($id,$event);
        $type->setParent($parent);
        $type->addChild($child);
        
        $type->validate();
    }
    
    /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage Column must have at least on child node
      */
    public function testValidationErrorNoChildren()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        $id        = 'columnNode';
        
        $type = new ColumnNode($id,$event);
        $type->setParent($parent);
        
        $type->validate();
        
    }
    
    /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage Column must have a name
      */
    public function testValidateFailsWithEmptyId()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        $id        = null;
        
        $type = new ColumnNode($id,$event);
        $type->setParent($parent);
        
        $type->validate();
        
    }
    
    
    public function testDBALInterfaceImplemented()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        $id        = 'columnNode';
        
        $type = new ColumnNode($id,$event);
        $type->setParent($parent);
        
        $dbalType = $this->getMockBuilder('Doctrine\DBAL\Types\Type')
                         ->setMethods(array('getSQLDeclaration','getName','convertToDatabaseValue'))
                         ->disableOriginalConstructor()
                         ->getMock();
                         
        $type->setDBALType($dbalType);
        
        $this->assertEquals($dbalType,$type->getDBALType());
        
    }
    
    
}
/* End of File */
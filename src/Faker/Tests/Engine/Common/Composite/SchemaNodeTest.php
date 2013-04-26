<?php
namespace Faker\Tests\Engine\Common\Composite;

use Faker\Components\Engine\Common\Composite\SchemaNode;
use Faker\Tests\Base\AbstractProject;

/**
  *  Test the node implementes Generator and Visitor Interfaces
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class SchemaNodeTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $id        = 'schemaNode';
            
        $type = new SchemaNode($id,$event);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\CompositeInterface',$type);
        
    }
    
    
    public function testCompositeProperties()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $id        = 'schemaNode';
        
        
        $type = new SchemaNode($id,$event);
        
        $this->assertEquals($id,$type->getId());        
        $this->assertEquals($event,$type->getEventDispatcher());
        $this->assertEquals(null,$type->getParent());
        $this->assertEquals(array(),$type->getChildren());
        
    }
    
    
    public function testValidateWithChildren()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $id        = 'schemaNode';
        $child   = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        
        $child->expects($this->once())
              ->method('validate');  
        
        $type = new SchemaNode($id,$event);
        $type->addChild($child);
        
        $type->validate();
    }
    
    /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage Schema must have at least 1 child node
      */
    public function testValidationErrorNoChildren()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $id        = 'schemaNode';
        
        $type = new SchemaNode($id,$event);
        
        $type->validate();
        
    }
    
    /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage Schema must have a name
      */
    public function testValidateFailsWithEmptyId()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $id        = null;
        
        $type = new SchemaNode($id,$event);
        
        $type->validate();
        
    }
    
    
}
/* End of File */
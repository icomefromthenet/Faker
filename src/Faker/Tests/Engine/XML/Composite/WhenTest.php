<?php
namespace Faker\Tests\Engine\XML\Composite;

use Faker\Components\Engine\XML\Composite\WhenNode;
use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Composite\GenericNode;

class WhenTest extends AbstractProject
{
    
    public function testImplementsInterfaces()
    {
        $id       = 'whenNode';
        $event    = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $whenNode = new WhenNode($id,$event);
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\CompositeInterface',$whenNode);
        $this->assertInstanceOf('Faker\Components\Engine\Common\OptionInterface',$whenNode);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\VisitorInterface',$whenNode);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\SerializationInterface',$whenNode);
    }
    
    
    public function testToXml()
    {
        $id    = 'whenNode';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $whenNode = new WhenNode($id,$event);
     
        $xml = $whenNode->toXml();
        $this->assertEquals('',$xml);
    }
    
    
    /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage The child node "at" at path "config" must be configured
      */
    public function testAtNotSet()
    {
        $id    = 'whenNode';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $whenNode = new WhenNode($id,$event);
        $whenNode->validate();
    }
    
     /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage When::at must be a integer
      */
    public function testAtNotSetWithInteger()
    {
        $id = 'whenNode';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $whenNode = new WhenNode($id,$event);
        $whenNode->setOption('at','100'); 
        $whenNode->validate();
    }
    
    public function testAtSet()
    {
        $id = 'whenNode';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $whenNode = new WhenNode($id,$event);
        $whenNode->setOption('at',100); 
        $whenNode->validate();
    }
    
    public function testValidatesChildren()
    {
        $id = 'whenNode';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();

        $whenNode = new WhenNode($id,$event);
        
        # create mock type node        
        $type = new GenericNode($id,$event);
        
        $genericNode = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\GenericNode')
                         ->setMethods(array('validate'))
                         ->disableOriginalConstructor()
                         ->getMock();
        
        $genericNode->expects($this->once())
                    ->method('validate');
                    
        $whenNode->addChild($genericNode);
        
        $whenNode->setOption('at',100); 
        $whenNode->validate();
        
        
    }
    
    
    public function testTypeInterfaceProperties()
    {
        $id         = 'fk_table_1';
        $event      = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $node       = new WhenNode($id,$event); 
        
        $utilities  = $this->getMock('Faker\Components\Engine\Common\Utilities');
        $generator  = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $locale     = $this->getMock('Faker\Locale\LocaleInterface');
        
        $node->setUtilities($utilities);
        $node->setLocale($locale);
        $node->setGenerator($generator);
        
        $this->assertEquals($utilities,$node->getUtilities());
        $this->assertEquals($locale,$node->getLocale());
        $this->assertEquals($generator,$node->getGenerator());
        
    }
    
}
/* End of File */
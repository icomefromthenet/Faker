<?php
namespace Faker\Tests\Engine\XML\Composite;

use Faker\Components\Engine\XML\Composite\WhenNode;
use Faker\Tests\Base\AbstractProject;

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
    
}
/* End of File */
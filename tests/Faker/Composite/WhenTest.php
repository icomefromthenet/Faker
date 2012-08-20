<?php
namespace Faker\Tests\Faker\Composite;

use Faker\Components\Faker\Composite\When,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Formatter\FormatEvents,
    Faker\Components\Faker\Formatter\GenerateEvent,
    Doctrine\DBAL\Types\Type as ColumnType,
    Faker\Tests\Base\AbstractProject;

class WhenTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $id = 'when_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $switch = 100;
        
        $this->assertInstanceOf('Faker\Components\Faker\Composite\CompositeInterface',new When($id,$parent,$event,$switch));
        
    }
    
    
    public function testProperties()
    {
        
        $id = 'when_1';
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $swap = 100;
    
        $alt = new When($id,$parent,$event,$swap);
        $alt->setOption('name','when');
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $alt->addChild($child_a);        
        $alt->addChild($child_b);        
        
        $this->assertEquals($alt->getChildren(),array($child_a,$child_b));
        $this->assertSame($alt->getEventDispatcher(),$event);
        $this->assertEquals($parent,$alt->getParent());
        $this->assertEquals($id,$alt->getId());       
        $this->assertEquals($swap,$alt->getSwap());
        
    }
    
    
    
}
/* End of File */
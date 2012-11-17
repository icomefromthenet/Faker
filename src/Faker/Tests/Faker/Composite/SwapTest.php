<?php
namespace Faker\Tests\Faker\Composite;

use Faker\Components\Faker\Composite\Swap,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Formatter\FormatEvents,
    Faker\Components\Faker\Formatter\GenerateEvent,
    Faker\Tests\Base\AbstractProject,
    Doctrine\DBAL\Types\Type as ColumnType;

class SwapTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $id = 'swap_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $this->assertInstanceOf('Faker\Components\Faker\Composite\CompositeInterface',new Swap($id,$parent,$event));
        
    }
    
    
    public function testProperties()
    {
        
        $id = 'swap_1';
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $alt = new Swap($id,$parent,$event);
        $alt->setOption('name','swap');
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $alt->addChild($child_a);        
        $alt->addChild($child_b);        
        
        $this->assertEquals($alt->getChildren(),array($child_a,$child_b));
        $this->assertSame($alt->getEventDispatcher(),$event);
        $this->assertEquals($parent,$alt->getParent());
        $this->assertEquals($id,$alt->getId());       
        
        
    }
    
    public function testGenerateNoRepeat()
    {
        
        $id = 'swap_1';
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $alt = new Swap($id,$parent,$event);
        $alt->setOption('name' ,'swap');
     
        $child_a = $this->getMockBuilder('\Faker\Components\Faker\Composite\When')->disableOriginalConstructor()->getMock();
        
        $child_a->expects($this->exactly(30))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('exampleA'));
            
        $child_a->expects($this->any())
                ->method('getSwap')
                ->will($this->returnValue(30));
            
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\When')->disableOriginalConstructor()->getMock();
        $child_b->expects($this->exactly(70)) 
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('exampleB'));
        
        $child_b->expects($this->any())
                ->method('getSwap')
                ->will($this->returnValue(70));
        

        $alt->addChild($child_a);        
        $alt->addChild($child_b);        
        
        
        for( $i = 1; $i <= 100; $i++) {
            $alt->generate($i,array());
        }
        
        
    }
    
    
    public function testGenerateRepeat()
    {
        
        $id = 'swap_1';
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $alt = new Swap($id,$parent,$event);
        $alt->setOption('name' ,'swap');
     
        $child_a = $this->getMockBuilder('\Faker\Components\Faker\Composite\When')->disableOriginalConstructor()->getMock();
        
        $child_a->expects($this->exactly(60))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('exampleA'));
            
        $child_a->expects($this->any())
                ->method('getSwap')
                ->will($this->returnValue(60));
            
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\When')->disableOriginalConstructor()->getMock();
        $child_b->expects($this->exactly(140)) 
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('exampleB'));
        
        $child_b->expects($this->any())
                ->method('getSwap')
                ->will($this->returnValue(140));
        

        $alt->addChild($child_a);        
        $alt->addChild($child_b);        
        
        
        for( $i = 1; $i <= 200; $i++) {
            $alt->generate($i,array());
        }
        
        
    }
    
}
/* End of File */
<?php
namespace Faker\Tests\Engine\Original\Composite;

use Faker\Components\Engine\Original\Composite\Pick,
    Faker\Components\Engine\Original\Composite\CompositeInterface,
    Faker\Components\Engine\Original\Formatter\FormatEvents,
    Faker\Components\Engine\Original\Formatter\GenerateEvent,
    Doctrine\DBAL\Types\Type as ColumnType,
    Faker\Tests\Base\AbstractProject;

class PickTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $id = 'pick_1';
        $probability = 50;
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        $pick = new Pick($id,$parent,$event,$probability);
        
        $this->assertInstanceOf('Faker\Components\Engine\Original\Composite\CompositeInterface',$pick);
        
    }
    
    
    public function testProperties()
    {
        
        $id = 'pick_1';
        $probability = 50;
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
    
        $pick = new Pick($id,$parent,$event,$probability);
        $pick->setOption('name','pick');
     
        $child_a = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        
          
        $pick->addChild($child_a);        
        $pick->addChild($child_b);        
        
        $this->assertEquals($pick->getChildren(),array($child_a,$child_b));
        $this->assertSame($pick->getEventDispatcher(),$event);
        $this->assertEquals($parent,$pick->getParent());
        $this->assertEquals($id,$pick->getId());
        
        
        
    }
    
    public function testGenerate()
    {
        
        $id = 'pick_1';
        $probability = 50;
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
    
        $random = new Pick($id,$parent,$event,$probability);
        $random->setOption('name','pick');
     
        $generatorA = $this->getMock('PHPStats\Generator\GeneratorInterface');  
        $generatorA->expects($this->once())
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(100))
                  ->will($this->returnValue(80));
    
        $generatorB = $this->getMock('PHPStats\Generator\GeneratorInterface');  
        $generatorB->expects($this->once())
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(100))
                  ->will($this->returnValue(30));
    
        
    
     
        $child_a = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->any())
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('exampleA'));
        
        
        $child_b = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->any())
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('exampleB'));
        
          
        $random->addChild($child_a);        
        $random->addChild($child_b);        
        
        $random->setGenerator($generatorA);
        $this->assertEquals('exampleB',$random->generate(1,array()));
        
        $random->setGenerator($generatorB);
        $this->assertEquals('exampleA',$random->generate(1,array()));
        
    }
    
    /**
      *  @expectedException Faker\Components\Engine\Original\Exception
      *  @expectedExceptionMessage Probability must be a number
      */
    public function testWithNonNumericProbability()
    {
        $id = 'pick_1';
        $probability = 'aaa';
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
    
        $random = new Pick($id,$parent,$event,$probability);
        
        
    }
    
    
    /**
      *  @expectedException Faker\Components\Engine\Original\Exception
      *  @expectedExceptionMessage Probability must be a between 0-1 or 0-100
      */
    public function testWithNegativeProbability()
    {
        $id = 'pick_1';
        $probability = -1;
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
    
        $random = new Pick($id,$parent,$event,$probability);
        
        
    }
    
    public function testWithZeroProbability()
    {
        $id = 'pick_1';
        $probability = 0;
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
    
        $random = new Pick($id,$parent,$event,$probability);
        $random->setOption('name','pick');
        $this->assertTrue(true);
    }
        
    
    
}
/* End of File */
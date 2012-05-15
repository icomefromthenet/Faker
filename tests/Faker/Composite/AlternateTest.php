<?php
namespace Faker\Tests\Faker\Composite;

use Faker\Components\Faker\Composite\Alternate,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Formatter\FormatEvents,
    Faker\Components\Faker\Formatter\GenerateEvent,
    Doctrine\DBAL\Types\Type as ColumnType,
    Faker\Tests\Base\AbstractProject;

class AlternateTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $id = 'alternate_1';
        $step = 50;
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $this->assertInstanceOf('Faker\Components\Faker\Composite\CompositeInterface',new Alternate($id,$parent,$event,$step));
        
    }
    
    
    public function testProperties()
    {
        
        $id = 'alternate_1';
        $step = 50;
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $alt = new Alternate($id,$parent,$event,$step);
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $alt->addChild($child_a);        
        $alt->addChild($child_b);        
        
        $this->assertEquals($alt->getChildren(),array($child_a,$child_b));
        $this->assertSame($alt->getEventDispatcher(),$event);
        $this->assertEquals($parent,$alt->getParent());
        $this->assertEquals($id,$alt->getId());       
        
        
    }
    
    public function testGenerate()
    {
        
        $id = 'alternate_1';
        $step = 2;
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $alt = new Alternate($id,$parent,$event,$step);
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->exactly(50))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('exampleA'));
        
        
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->exactly(50))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('exampleB'));
        
          
        $alt->addChild($child_a);        
        $alt->addChild($child_b);        
        
        
        for( $i = 1; $i <= 100; $i++) {
            $alt->generate($i,array());
        }
        
        
    }
    
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Step must be an integer
      */
    public function testWithNonNumericProbability()
    {
        $id = 'alternate_1';
        $step = 'aaa';
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        new Alternate($id,$parent,$event,$step);
        
        
    }
    
    
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Step must be greater than 0
      */
    public function testWithNegativeProbability()
    {
        $id = 'alternate_1';
        $step = -1;
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        new Alternate($id,$parent,$event,$step);
        
        
    }
    
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Step must be greater than 0
      */
    public function testWithZeroProbability()
    {
        $id = 'alternate_1';
        $step = 0;
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        new Alternate($id,$parent,$event,$step);
        
        $this->assertTrue(true);
    }
        
    
    
}
/* End of File */
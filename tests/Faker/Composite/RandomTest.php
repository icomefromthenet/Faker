<?php
namespace Faker\Tests\Faker\Composite;

use Faker\Components\Faker\Composite\Random,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Formatter\FormatEvents,
    Faker\Components\Faker\Formatter\GenerateEvent,
    Doctrine\DBAL\Types\Type as ColumnType,
    Faker\Tests\Base\AbstractProject;

class RandomTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $id = 'random_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $random = new Random($id,$parent,$event);
        $this->assertInstanceOf('Faker\Components\Faker\Composite\CompositeInterface',$random);
        
    }
    
    
    public function testProperties()
    {
        
        $id = 'random_1';
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $random = new Random($id,$parent,$event);
        $random->setOption('name','random');
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $random->addChild($child_a);        
        $random->addChild($child_b);        
        
        $this->assertEquals($random->getChildren(),array($child_a,$child_b));
        $this->assertSame($random->getEventDispatcher(),$event);
        $this->assertEquals($parent,$random->getParent());
        $this->assertEquals($id,$random->getId());       
        
        
    }
    
    public function testGenerate()
    {
        
        $id = 'random_1';
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $random = new Random($id,$parent,$event);
        $random->setOption('name','random');
     
        $generatorA = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $generatorA->expects($this->exactly(1))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(1))
                  ->will($this->returnValue(0));
        
        $generatorB = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $generatorB->expects($this->exactly(1))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(1))
                  ->will($this->returnValue(1));
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->any())
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('exampleA'));
        
        
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->any())
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('exampleB'));
        
          
        $random->addChild($child_a);        
        $random->addChild($child_b);        

        $random->setGenerator($generatorA);
        $this->assertEquals('exampleA',$random->generate(1,array()));
        
        $random->setGenerator($generatorB);
        $this->assertEquals('exampleB',$random->generate(1,array()));
        
    }
    
    
}
/* End of File */
<?php
namespace Faker\Tests\Faker\Type;

use Faker\Components\Faker\Type\Range,
    Faker\Tests\Base\AbstractProject;

class RangeTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();

        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
            
        $type = new Range($id,$parent,$event,$utilities,$generator);
        
        $this->assertInstanceOf('\\Faker\\Components\\Faker\\TypeInterface',$type);
    
    }
    
    //--------------------------------------------------------------------------
    
    public function testDefaultConfig()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
            
        $type = new Range($id,$parent,$event,$utilities,$generator);
        $type->setOption('min', 1 );
        $type->setOption('max', 100);
        $type->setOption('step', 1);
        $type->setOption('name','range');
        $type->merge();        
        
        $this->assertEquals($type->getOption('min'),1);
        $this->assertEquals($type->getOption('max'),100);
        $this->assertEquals($type->getOption('step'),1);
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Range::max Numeric is required
      */
    public function testConfigNotNumericMax()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
            
        $type = new Range($id,$parent,$event,$utilities,$generator);
        $type->setOption('max', 'aaa');
        $type->setOption('min' ,1);
        $type->setOption('name','range');
        $type->merge();        
        
    }
    
    //  -------------------------------------------------------------------------
   
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Range::min Numeric is required
      */
    public function testConfigNotNumericMin()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
            
        $type = new Range($id,$parent,$event,$utilities,$generator);
        $type->setOption('max' , 100);
        $type->setOption('min' ,'aa');
        $type->setOption('name','range');
        $type->merge();        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Range::Step option should be numeric or bool(false) to use random step
      */
    public function testNotNumericStep()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
            
        $type = new Range($id,$parent,$event,$utilities,$generator);
        $type->setOption('step' , 'bbb');
        $type->setOption('max', 100);
        $type->setOption('min' , 1);
        $type->setOption('name','range');
        $type->merge();        
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
            
        $type = new Range($id,$parent,$event,$utilities,$generator);
        
        # test with start > 0
        $type->setOption('min',1);
        $type->setOption('max',4);
        $type->setOption('step',1);
        $type->setOption('name','range');
        
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals(1,$type->generate(1,array()));
        $this->assertEquals(2,$type->generate(2,array()));
        $this->assertEquals(3,$type->generate(3,array()));
        $this->assertEquals(4,$type->generate(4,array()));
        
        $this->assertEquals(1,$type->generate(5,array()));
        $this->assertEquals(2,$type->generate(6,array()));
        $this->assertEquals(3,$type->generate(7,array()));
        $this->assertEquals(4,$type->generate(8,array()));
        
    }
    
    public function testGenerateRandomStep()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
            
        $generator->expects($this->once())
                 ->method('generate')
                 ->with($this->equalTo(1),$this->equalTo(4))
                 ->will($this->returnValue(1));
            
        $type = new Range($id,$parent,$event,$utilities,$generator);
        
        # test with start > 0
        $type->setOption('min',1);
        $type->setOption('max',4);
        $type->setOption('random',true);
        $type->setOption('name','range');
        
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals(1,$type->generate(1,array()));
        
    }
    
}
/*End of file */
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
      
            
        $type = new Range($id,$parent,$event,$utilities);
        
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
            
        $type = new Range($id,$parent,$event,$utilities);
        $type->setOption('min', 1 );
        $type->setOption('max', 100);
        $type->setOption('step', 1);
        $type->merge();        
        
        $this->assertEquals($type->getOption('min'),1);
        $this->assertEquals($type->getOption('max'),100);
        $this->assertEquals($type->getOption('step'),1);
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Number::max Numeric is required
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
            
        $type = new Range($id,$parent,$event,$utilities);
        $type->setOption('max', 'aaa');
        $type->setOption('min' ,1);
        $type->merge();        
        
    }
    
    //  -------------------------------------------------------------------------
   
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Number::min Numeric is required
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
            
        $type = new Range($id,$parent,$event,$utilities);
        $type->setOption('max' , 100);
        $type->setOption('min' ,'aa');
        $type->merge();        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Number::step Numeric is required
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
            
        $type = new Range($id,$parent,$event,$utilities);
        $type->setOption('step' , 'bbb');
        $type->setOption('max', 100);
        $type->setOption('min' , 1);
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
            
        $type = new Range($id,$parent,$event,$utilities);
        
        # test with start > 0
        $type->setOption('min',1);
        $type->setOption('max',4);
        $type->setOption('step',1);
        
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
    
}
/*End of file */
<?php
namespace Faker\Tests\Engine\Original\Type;

use Faker\Components\Engine\Original\Type\AutoIncrement,
    Faker\Tests\Base\AbstractProject;

class AutoIncrementTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Original\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();

        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface'); 
            
        $type = new AutoIncrement($id,$parent,$event,$utilities,$generator);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Original\\TypeInterface',$type);
    
    }
    
    //--------------------------------------------------------------------------
    
    public function testDefaultConfig()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Original\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $type = new AutoIncrement($id,$parent,$event,$utilities,$generator);
        $type->setOption('name','autoincrement');
        $type->merge();        
        
        $this->assertEquals($type->getOption('start'),1);
        $this->assertEquals($type->getOption('increment'),1);
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\Original\Exception
      *  @expectedExceptionMessage Unrecognized options "aaaa" under "config"
      */
    public function testConfigBadValue()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Original\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $type = new AutoIncrement($id,$parent,$event,$utilities,$generator);
        $type->setOption('aaaa','bbb');
        $type->setOption('name','autoincrement');
        $type->merge();        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\Original\Exception
      *  @expectedExceptionMessage AutoIncrement::Increment option must be numeric
      */
    public function testNotNumericIncrement()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Original\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $type = new AutoIncrement($id,$parent,$event,$utilities,$generator);
        $type->setOption('increment' , 'bbb');
        $type->setOption('name','autoincrement');
        $type->merge();        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Original\Exception
      *  @expectedExceptionMessage AutoIncrement::Start option must be numeric
      */
    public function testNotNumericStart()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Original\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
                      
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');                      
            
        $type = new AutoIncrement($id,$parent,$event,$utilities,$generator);
        $type->setOption('start','bbb');
        $type->setOption('name','autoincrement');
        $type->merge();        
    }
    
    
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Original\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();

        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $type = new AutoIncrement($id,$parent,$event,$utilities,$generator);
        
        # test with start > 0
        $type->setOption('start',1);
        $type->setOption('increment',4);
        $type->setOption('name','autoincrement');
        $type->validate(); 
         
        $this->assertEquals(1,$type->generate(1,array()));
        $this->assertEquals(5,$type->generate(2,array()));
        $this->assertEquals(9,$type->generate(3,array()));
        
        
        # test with start at 0
        $type = new AutoIncrement($id,$parent,$event,$utilities,$generator);
        $type->setOption('start',0);
        $type->setOption('increment',4);
        $type->setOption('name','autoincrement');
        
        
        $type->validate(); 
         
        $this->assertEquals(0,$type->generate(1,array()));
        $this->assertEquals(4,$type->generate(2,array()));
        $this->assertEquals(8,$type->generate(3,array()));
 
 
 
        # test with non int increment
        $type = new AutoIncrement($id,$parent,$event,$utilities,$generator);
        $type->setOption('start',0);
        $type->setOption('increment',0.5);
        $type->setOption('name','autoincrement');
        $type->validate(); 
         
        $this->assertEquals(0,$type->generate(1,array()));
        $this->assertEquals(0.5,$type->generate(2,array()));
        $this->assertEquals(1,$type->generate(3,array()));
        
 
    }
    
    
    
}
/*End of file */

<?php
namespace Faker\Tests\Engine\Original\Type;

use Faker\Components\Engine\Original\Type\Date,
    Faker\Tests\Base\AbstractProject;

class DateTest extends AbstractProject
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
            
        $type = new Date($id,$parent,$event,$utilities,$generator);
        
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
            
        $type = new Date($id,$parent,$event,$utilities,$generator);
        $type->setOption('start', '14-01-1983');
        $type->setOption('name','date');
        $type->merge();        
        
        $this->assertInstanceOf('\DateTime',$type->getOption('start'));
        
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
            
        $type = new Date($id,$parent,$event,$utilities,$generator);
        $type->setOption('aaaa' , 'bbb');
        $type->setOption('name','date');
        $type->merge();        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\Original\Exception
      *  @expectedExceptionMessage Invalid configuration for path "config.start": DateTime::__construct(): Failed to parse time string (bbb) at position 0 (b): The timezone could not be found in the database 
      */
    public function testStartInvalid()
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
            
        $type = new Date($id,$parent,$event,$utilities,$generator);
        $type->setOption('start' , 'bbb');
        $type->setOption('name','date');
        $type->merge();        
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\Original\Exception
      *  @expectedExceptionMessage Invalid configuration for path "config.max": DateTime::__construct(): Failed to parse time string (bbb) at position 0 (b): The timezone could not be found in the database 
      */
    public function testMaxInvalid()
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
            
        $type = new Date($id,$parent,$event,$utilities,$generator);
        $type->setOption('max' , 'bbb');
        $type->setOption('start' ,'1st August 2007');
        $type->setOption('name','date');
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
        
        $rnd_date = new \DateTime();
        $generator->expects($this->once())
                  ->method('generate')
                  ->with($this->isType('integer'),$this->isType('integer'))
                  ->will($this->returnValue($rnd_date->getTimestamp()));
        
            
        $type = new Date($id,$parent,$event,$utilities,$generator);
        
        # test with start > 0
        $type->setOption('start','today');
        $type->setOption('modify','+ 1 hour');
        $type->setOption('name','date');
        $type->merge();
        $type->validate(); 
         
        $this->assertInstanceOf('\DateTime',$type->generate(1,array()));
        $this->assertInstanceOf('\DateTime',$type->generate(1,array()));
      
    
        # test with max
        $type->setOption('start','today');
        $type->setOption('modify','+ 1 hour');
        $type->setOption('max','today +3 hours');
        $type->setOption('name','date');
        $type->merge();
        $type->validate(); 
        
       $dte1 = $type->generate(1,array());
       $dte2 = $type->generate(2,array());
       $dte3 = $type->generate(3,array());
       $dte4 = $type->generate(4,array());
       $dte5 = $type->generate(4,array());
      
       # test if date has been reset once max reached
       $this->assertEquals($dte1->format('U'),$dte5->format('U'));
       
       # iterations are not equal ie modify is appied on each loop
       $this->assertFalse($dte1->format('U') === $dte2->format('U'));
       $this->assertFalse($dte2->format('U') === $dte3->format('U')); 
       $this->assertFalse($dte3->format('U') === $dte4->format('U')); 
       $this->assertFalse($dte4->format('U') === $dte5->format('U')); 
   
       # test with modify
       $type->setOption('modify',false);
       $type->setOption('random',true);
       $this->assertEquals($rnd_date->getTimestamp(),$type->generate(1,array())->getTimestamp());
       
       # test fixed date
       $start = new \DateTime();
       $type->setOption('modify',false);
       $type->setOption('random',false);
       $type->setOption('start',$start);
       $this->assertEquals($start->getTimestamp(),$type->generate(1,array())->getTimestamp());
       $this->assertEquals($start->getTimestamp(),$type->generate(2,array())->getTimestamp());
       $this->assertEquals($start->getTimestamp(),$type->generate(3,array())->getTimestamp());
    }
    
    
    
}
/*End of file */

<?php
namespace Faker\Tests\Faker\Type;

use Faker\Components\Faker\Type\Date,
    Faker\Tests\Base\AbstractProject;

class DateTest extends AbstractProject
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
            
        $type = new Date($id,$parent,$event,$utilities,$generator);
        
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
            
        $type = new Date($id,$parent,$event,$utilities,$generator);
        $type->setOption('start', '14-01-1983');
        $type->merge();        
        
        $this->assertInstanceOf('\DateTime',$type->getOption('start'));
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Unrecognized options "aaaa" under "config"
      */
    public function testConfigBadValue()
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
            
        $type = new Date($id,$parent,$event,$utilities,$generator);
        $type->setOption('aaaa' , 'bbb'); 
        $type->merge();        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Invalid configuration for path "config.start": DateTime::__construct(): Failed to parse time string (bbb) at position 0 (b): The timezone could not be found in the database 
      */
    public function testStartInvalid()
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
            
        $type = new Date($id,$parent,$event,$utilities,$generator);
        $type->setOption('start' , 'bbb');
        $type->merge();        
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Invalid configuration for path "config.max": DateTime::__construct(): Failed to parse time string (bbb) at position 0 (b): The timezone could not be found in the database 
      */
    public function testMaxInvalid()
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
            
        $type = new Date($id,$parent,$event,$utilities,$generator);
        $type->setOption('max' , 'bbb');
        $type->setOption('start' ,'1st August 2007');
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
            
        $type = new Date($id,$parent,$event,$utilities,$generator);
        
        # test with start > 0
        $type->setOption('start','today');
        $type->setOption('modify','+ 1 hour');
        $type->merge();
        $type->validate(); 
         
        $this->assertInstanceOf('\DateTime',$type->generate(1,array()));
        $this->assertInstanceOf('\DateTime',$type->generate(1,array()));
      
    
        # test with max
        $type->setOption('start','today');
        $type->setOption('modify','+ 1 hour');
        $type->setOption('max','today +3 hours');
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
   
    }
    
    
    
}
/*End of file */

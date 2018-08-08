<?php
namespace Faker\Tests\Engine\Common\Type;

use Faker\Components\Engine\Common\Type\Date;
use Faker\Tests\Base\AbstractProject;

class DateTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');    
            
        $type = new Date();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Type\\TypeInterface',$type);
    
    }
    
    //--------------------------------------------------------------------------
    
    public function testDefaultConfig()
    {
         $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');    
            
        $type = new Date();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('start', '14-01-1983');
        
        $this->assertTrue($type->validate());        
        $this->assertInstanceOf('\DateTime',$type->getOption('start'));
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Unrecognized option "aaaa" under "config"
      */
    public function testConfigBadValue()
    {
         $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');    
            
        $type = new Date();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);

        $type->setOption('aaaa' , 'bbb');
        $type->validate();        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Invalid configuration for path "config.start": DateTime::__construct(): Failed to parse time string (bbb) at position 0 (b): The timezone could not be found in the database 
      */
    public function testStartInvalid()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');    
            
        $type = new Date();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);

        $type->setOption('start' , 'bbb');
        $type->validate();        
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Invalid configuration for path "config.max": DateTime::__construct(): Failed to parse time string (bbb) at position 0 (b): The timezone could not be found in the database 
      */
    public function testMaxInvalid()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');    
            
        $type = new Date();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);

        $type->setOption('max' , 'bbb');
        $type->setOption('start' ,'1st August 2007');
        $type->validate();        
        
        
    }
        
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');    
        
        $rnd_date = new \DateTime();
        $generator->expects($this->once())
                  ->method('generate')
                  ->with($this->isType('integer'),$this->isType('integer'))
                  ->will($this->returnValue($rnd_date->getTimestamp()));
        
            
        $type = new Date();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);

        
        # test with start > 0
        $type->setOption('start','today');
        $type->setOption('modify','+ 1 hour');
        $type->validate(); 
        
        $values = array(); 
        $this->assertInstanceOf('\DateTime',$type->generate(1,$values));
        $this->assertInstanceOf('\DateTime',$type->generate(1,$values));
      
    
        # test with max
        $type->setOption('start','today');
        $type->setOption('modify','+ 1 hour');
        $type->setOption('max','today +3 hours');
        $type->validate(); 
       
       $values = array();  
       $dte1 = $type->generate(1,$values);
       $dte2 = $type->generate(2,$values);
       $dte3 = $type->generate(3,$values);
       $dte4 = $type->generate(4,$values);
       $dte5 = $type->generate(4,$values);
      
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
       $this->assertEquals($rnd_date->getTimestamp(),$type->generate(1,$values)->getTimestamp());
       
       # test fixed date
       $start = new \DateTime();
       $type->setOption('modify',false);
       $type->setOption('random',false);
       $type->setOption('start',$start);
       $this->assertEquals($start->getTimestamp(),$type->generate(1,$values)->getTimestamp());
       $this->assertEquals($start->getTimestamp(),$type->generate(2,$values)->getTimestamp());
       $this->assertEquals($start->getTimestamp(),$type->generate(3,$values)->getTimestamp());
    }
    
    
    
}
/*End of file */

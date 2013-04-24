<?php
namespace Faker\Tests\Engine\Common\Type;

use Faker\Components\Engine\Common\Type\Range;
use Faker\Tests\Base\AbstractProject;

class RangeTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
            
        $type = new Range();
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
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
            
        $type = new Range();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('min', 1 );
        $type->setOption('max', 100);
        $type->setOption('step', 1);

        $type->validate();        
        
        $this->assertEquals($type->getOption('min'),1);
        $this->assertEquals($type->getOption('max'),100);
        $this->assertEquals($type->getOption('step'),1);
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Range::max Numeric is required
      */
    public function testConfigNotNumericMax()
    {
       $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
            
        $type = new Range();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('max', 'aaa');
        $type->setOption('min' ,1);
        
        $type->validate();        
        
    }
    
    //---------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Range::Round option should be a positive integer >= 0
      */
    public function testConfigRoundNotInteger()
    {
       $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
            
        $type = new Range();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('max', 5);
        $type->setOption('min' ,1);
        $type->setOption('round' ,'aaa');
                
        $type->validate();        
        
    }
    
     /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Range::Round option should be a positive integer >= 0
      */
    public function testConfigRoundNotPositiveInteger()
    {
       $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
            
        $type = new Range();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('max', 5);
        $type->setOption('min' ,1);
        $type->setOption('round' ,-1);
        
        $type->validate();        
        
    }
    
    //  -------------------------------------------------------------------------
   
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Range::min Numeric is required
      */
    public function testConfigNotNumericMin()
    {
       $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
            
        $type = new Range();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('max' , 100);
        $type->setOption('min' ,'aa');
        
        $type->validate();        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Range::Step option should be numeric or bool(false) to use random step
      */
    public function testNotNumericStep()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
            
        $type = new Range();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('step' , 'bbb');
        $type->setOption('max', 100);
        $type->setOption('min' , 1);
        
        $type->validate();        
    }
    
     //  -------------------------------------------------------------------------
   
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Range:: windowStep must be an number
      */
    public function testNotNumericWindowStep()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
            
        $type = new Range();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('windowStep' , 'aaa');
        $type->setOption('max', 100);
        $type->setOption('min' , 1);
        
        $type->validate();        
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
       $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
            
        $type = new Range();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        # test with start > 0
        $type->setOption('min',1);
        $type->setOption('max',4);
        $type->setOption('step',1);
        
        $type->validate(); 
        
        $values = array();  
        $this->assertEquals(1,$type->generate(1,$values));
        $this->assertEquals(2,$type->generate(2,$values));
        $this->assertEquals(3,$type->generate(3,$values));
        $this->assertEquals(4,$type->generate(4,$values));
        
        $this->assertEquals(1,$type->generate(5,$values));
        $this->assertEquals(2,$type->generate(6,$values));
        $this->assertEquals(3,$type->generate(7,$values));
        $this->assertEquals(4,$type->generate(8,$values));
        
    }
    
    public function testGenerateWithWindow()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
            
        $type = new Range();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        # test with start > 0
        $type->setOption('min',1);
        $type->setOption('max',4);
        $type->setOption('step',1);
        $type->setOption('windowStep',1);
        
        $type->validate(); 
        
        $values = array();  
        $this->assertEquals(1,$type->generate(1,$values));
        $this->assertEquals(2,$type->generate(2,$values));
        $this->assertEquals(3,$type->generate(3,$values));
        $this->assertEquals(4,$type->generate(4,$values));
        
        $this->assertEquals(2,$type->generate(5,$values));
        $this->assertEquals(3,$type->generate(6,$values));
        $this->assertEquals(4,$type->generate(7,$values));
        $this->assertEquals(5,$type->generate(8,$values));
        
    }
    
    public function testGenerateRandomStep()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
            
        $generator->expects($this->once())
                 ->method('generate')
                 ->with($this->equalTo(1),$this->equalTo(4))
                 ->will($this->returnValue(1));
            
        $type = new Range();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        # test with start > 0
        $type->setOption('min',1);
        $type->setOption('max',4);
        $type->setOption('random',true);
        
        $type->validate(); 
        $values = array();  
        $this->assertEquals(1,$type->generate(1,$values));
        
    }
    
    public function testGenerateRandomStepWithRounding()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
            
        $generator->expects($this->once())
                 ->method('generate')
                 ->with($this->equalTo(1),$this->equalTo(4))
                 ->will($this->returnValue(2.334));
            
        $type = new Range();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        # test with start > 0
        $type->setOption('min',1);
        $type->setOption('max',4);
        $type->setOption('random',true);
        $type->setOption('round',2);
        
        $type->validate(); 
        $values = array(); 
        $this->assertEquals(2.33,$type->generate(1,$values));
        
    }
    
    
}
/*End of file */
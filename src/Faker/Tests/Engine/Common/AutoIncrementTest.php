<?php
namespace Faker\Tests\Engine\Common\Type;

use Faker\Components\Engine\Common\Type\AutoIncrement,
    Faker\Tests\Base\AbstractProject;

class AutoIncrementTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
            
        $type = new AutoIncrement();
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
        
            
        $type = new AutoIncrement();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $this->assertTrue($type->validate());        
        
        $this->assertEquals($type->getOption('start'),1);
        $this->assertEquals($type->getOption('increment'),1);
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Unrecognized options "aaaa" under "config"
      */
    public function testConfigBadValue()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
            
        $type = new AutoIncrement();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('aaaa','bbb');
        $type->validate();        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage AutoIncrement::Increment option must be numeric
      */
    public function testNotNumericIncrement()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     
            
        $type = new AutoIncrement();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('increment' , 'bbb');
        $type->validate();        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage AutoIncrement::Start option must be numeric
      */
    public function testNotNumericStart()
    {
       $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');                          
            
        $type = new AutoIncrement();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('start','bbb');
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
            
        $type = new AutoIncrement();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        # test with start > 0
        $type->setOption('start',1);
        $type->setOption('increment',4);
        $type->validate(); 
         
        $this->assertEquals(1,$type->generate(1,array()));
        $this->assertEquals(5,$type->generate(2,array()));
        $this->assertEquals(9,$type->generate(3,array()));
        
        
        # test with start at 0
        $type = new AutoIncrement();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('start',0);
        $type->setOption('increment',4);
        
        
        $type->validate(); 
         
        $this->assertEquals(0,$type->generate(1,array()));
        $this->assertEquals(4,$type->generate(2,array()));
        $this->assertEquals(8,$type->generate(3,array()));
 
 
 
        # test with non int increment
        $type = new AutoIncrement();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('start',0);
        $type->setOption('increment',0.5);
        $type->validate(); 
         
        $this->assertEquals(0,$type->generate(1,array()));
        $this->assertEquals(0.5,$type->generate(2,array()));
        $this->assertEquals(1,$type->generate(3,array()));
        
 
    }
    
    
    
}
/*End of file */

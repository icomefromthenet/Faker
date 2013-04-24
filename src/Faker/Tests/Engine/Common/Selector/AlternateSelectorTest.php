<?php
namespace Faker\Tests\Engine\Common\Selector;

use Faker\Components\Engine\Common\Selector\AlternateSelector;
use Faker\Tests\Base\AbstractProject;

class AlternateSelectorTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new AlternateSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Type\\TypeInterface',$type);
        
        
    }
    
    
    public function testValidateOk()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new AlternateSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('set',5);
        $type->setOption('step',1);
        
        $this->assertTrue($type->validate());
        
    }
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Invalid configuration for path "config.step": AlternateSelector::Step integer is required and must be > 0
      */
    public function testWithNonNumericStep()
    {
       $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new AlternateSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('set',5);
        $type->setOption('step','aaaa');
        
        $type->validate();
        
    }
    
     /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage AlternateSelector::Step integer is required and must be > 0
      */
    public function testWithNegativeStep()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new AlternateSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('set',5);
        $type->setOption('step',-1);
        
        $type->validate();
        
        
    }
    
     /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage AlternateSelector::Step integer is required and must be > 0
      */
    public function testWithZeroStep()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new AlternateSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('set',5);
        $type->setOption('step',0);
        
        $type->validate();
    }
    
     /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage AlternateSelector::Set integer is required and must be > 0
      */
    public function testWithZeroSet()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new AlternateSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('set',0);
        $type->setOption('step',1);
        
        $type->validate();
    }
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage AlternateSelector::Set integer is required and must be > 0
      */
    public function testWithBadSet()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new AlternateSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('set','aaa');
        $type->setOption('step',1);
        
        $type->validate();
    }
    
    public function testGenerate()
    {
        $values = array(); 
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new AlternateSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('set',5);
        $type->setOption('step',1);
        
        $this->assertTrue($type->validate());
        
        $this->assertEquals(1,$type->generate(0,$values));
        $this->assertEquals(2,$type->generate(0,$values));
        $this->assertEquals(3,$type->generate(0,$values));
        $this->assertEquals(4,$type->generate(0,$values));
        $this->assertEquals(5,$type->generate(0,$values));
        
        $type->setOption('set',3);
        $type->setOption('step',3);
        
        $this->assertTrue($type->validate());
        
        $this->assertEquals(1,$type->generate(0,$values));
        $this->assertEquals(1,$type->generate(0,$values));
        $this->assertEquals(1,$type->generate(0,$values));
        $this->assertEquals(2,$type->generate(0,$values));
        $this->assertEquals(2,$type->generate(0,$values));
        $this->assertEquals(2,$type->generate(0,$values));
        $this->assertEquals(3,$type->generate(0,$values));
        $this->assertEquals(3,$type->generate(0,$values));
        $this->assertEquals(3,$type->generate(0,$values));
        
        # loop ok
        $this->assertEquals(1,$type->generate(0,$values));
        $this->assertEquals(1,$type->generate(0,$values));
        $this->assertEquals(1,$type->generate(0,$values));
        $this->assertEquals(2,$type->generate(0,$values));
        $this->assertEquals(2,$type->generate(0,$values));
        $this->assertEquals(2,$type->generate(0,$values));
        $this->assertEquals(3,$type->generate(0,$values));
        $this->assertEquals(3,$type->generate(0,$values));
        $this->assertEquals(3,$type->generate(0,$values));
        
    }
    
}
/* End of File */
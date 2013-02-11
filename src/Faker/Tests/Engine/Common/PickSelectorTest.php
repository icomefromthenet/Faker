<?php
namespace Faker\Tests\Engine\Common;

use Faker\Components\Engine\Common\Selector\PickSelector;
use Faker\Tests\Base\AbstractProject;

class PickSelectorTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new PickSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Type\\TypeInterface',$type);
        
        
    }
    
    
     /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Invalid configuration for path "config.probability": PickSelector::Probability must be between 0 and  1
      */
    public function testWithNonNumericProbability()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new PickSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('probability','aaa');
        
        $type->validate();
    }
    
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Invalid configuration for path "config.probability": PickSelector::Probability must be between 0 and  1
      */
    public function testWithNegativeProbability()
    {
       $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new PickSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('probability',-1);
        
        $type->validate();
        
        
    }
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage  Invalid configuration for path "config.probability": PickSelector::Probability must be between 0 and  1
      */
    public function testWithZeroProbability()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new PickSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('probability',0);
        
        $type->validate();
    }
    
    
    public function testGenerate()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        $type = new PickSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('probability',0.5);
        $type->validate();
     
        $generator->expects($this->at(0))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(100))
                  ->will($this->returnValue(80));
                  
        $generator->expects($this->at(1))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(100))
                  ->will($this->returnValue(40));
                  
                  
        $generator->expects($this->at(2))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(100))
                  ->will($this->returnValue(50));
                  
        $generator->expects($this->at(3))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(100))
                  ->will($this->returnValue(80));
        
        $generator->expects($this->at(4))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(100))
                  ->will($this->returnValue(100));
    
        
        $this->assertEquals(0,$type->generate(1,array()));
        $this->assertEquals(1,$type->generate(1,array()));
        $this->assertEquals(1,$type->generate(1,array()));
        $this->assertEquals(0,$type->generate(1,array()));
        $this->assertEquals(0,$type->generate(1,array()));
        
        
    }
  
}
/* End of File */
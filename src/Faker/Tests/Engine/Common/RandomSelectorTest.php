<?php
namespace Faker\Tests\Engine\Common;

use Faker\Components\Engine\Common\Selector\RandomSelector;
use Faker\Tests\Base\AbstractProject;



class RandomSelectorTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new RandomSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Type\\TypeInterface',$type);
        
        
    }
    
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage The child node "set" at path "config" must be configured.
      */
    public function testConfigErrorNotSetSize()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new RandomSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->validate();
        
    }
    
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage RandomSelector::Set integer is required and must be > 0
      */
    public function testConfigErrorNotIntegerSetSize()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new RandomSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('set','aaaa');
        
        $type->validate();
        
    }
    
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage RandomSelector::Set integer is required and must be > 0
      */
    public function testConfigErrorSetSizeOutRange()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
        
        
        $type = new RandomSelector();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('set',0);
        
        $type->validate();
        
    }
    
    
    public function testGenerate()
    {
        
       $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');
    
       
        
            
        $generatorA = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $generatorA->expects($this->at(0))
                  ->method('generate')
                  ->with($this->equalTo(1),$this->equalTo(1))
                  ->will($this->returnValue(1));
                  
        $generatorA->expects($this->at(1))
                  ->method('generate')
                  ->with($this->equalTo(1),$this->equalTo(1))
                  ->will($this->returnValue(1.8));
                  
        $generatorA->expects($this->at(2))
                  ->method('generate')
                  ->with($this->equalTo(1),$this->equalTo(1))
                  ->will($this->returnValue(1.3));
        
         $type = new RandomSelector();
        $type->setGenerator($generatorA);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        $type->setOption('set',1);
        $type->validate();
        
        $this->assertEquals(1,$type->generate(1,array()));
        $this->assertEquals(2,$type->generate(1,array())); # rounds up
        $this->assertEquals(1,$type->generate(1,array())); # rounds down
        
        
    }
    
    
}
/* End of File */
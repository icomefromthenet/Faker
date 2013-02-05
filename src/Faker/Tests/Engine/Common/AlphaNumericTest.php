<?php
namespace Faker\Tests\Engine\Common;

use Faker\Components\Engine\Common\Type\AlphaNumeric,
    Faker\Tests\Base\AbstractProject;

class AlphaNumericTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');    
            
        $type = new AlphaNumeric();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Type\\TypeInterface',$type);
    }
    
    
    public function testGenerate()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface'); 
                          
        $utilities->expects($this->once())
                   ->method('generateRandomAlphanumeric')
                   ->with($this->equalTo('ccCC'))
                   ->will($this->returnValue('dgHJ'));
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        
            
        $type = new AlphaNumeric();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        $type->setOption('format','ccCC');

        $this->assertTrue($type->validate()); 
        $this->assertEquals('dgHJ',$type->generate(1,array()));
    }
    
    
    public function testGenerateWithEqualRepeat()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface'); 
                          
        $utilities->expects($this->once())
                   ->method('generateRandomAlphanumeric')
                   ->with($this->equalTo('ccCC'))
                   ->will($this->returnValue('dgHJ'));
        
            
        $type = new AlphaNumeric();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
       
        $type->setOption('format','ccCC');
        $type->setOption('repeatMax',1);
        $type->setOption('repeatMin',1);
        $type->validate(); 
         
        $this->assertEquals('dgHJ',$type->generate(1,array()));
    }
    
    
    public function testGenerateWithNotEqualRepeat()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface'); 
                          
        $utilities->expects($this->any())
                   ->method('generateRandomAlphanumeric')
                   ->with($this->equalTo('ccCC'))
                   ->will($this->returnValue('dgHJ'));
        
        $generator->expects($this->exactly(3))
                  ->method('generate')
                  ->with($this->equalTo(1),$this->equalTo(5))
                  ->will($this->onConsecutiveCalls(1, 2, 3));
        
            
        $type = new AlphaNumeric();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
       
        $type->setOption('format','ccCC');
        $type->setOption('repeatMax',5);
        $type->setOption('repeatMin',1);
        $type->validate(); 
         
        $length = strlen($type->generate(1,array())); 
        $this->assertGreaterThanOrEqual(4, $length);
        $this->assertLessThanOrEqual(20, $length);

        
        $length = strlen($type->generate(2,array())); 
        $this->assertGreaterThanOrEqual(8, $length);
        $this->assertLessThanOrEqual(20, $length);

        
        $length = strlen($type->generate(3,array())); 
        $this->assertGreaterThanOrEqual(12, $length);
        $this->assertLessThanOrEqual(20, $length);

        
    }
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Invalid configuration for path "config.repeatMin": AlphaNumeric::repeatMin value must be an integer greater than or equal to zero
      */
    public function testConfigExceptionRepeatMinZero()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface'); 
                          
        $type = new AlphaNumeric();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
       
        $type->setOption('format','ccCC');
        $type->setOption('repeatMin',-1);
        $type->validate(); 
        
    }
    
     /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage AlphaNumeric::repeatMax value must be an integer greater than zero
      */
    public function testConfigExceptionRepeatMaxZero()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface'); 
                          
        $type = new AlphaNumeric();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
       
        $type->setOption('format','ccCC');
        $type->setOption('repeatMax',-1);
        $type->validate(); 
        
    }
    
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage AlphaNumeric::Repeat range is not valid minimum is > maximum
      */
    public function testConfigExceptionRepeatMinGreaterThanRepeatMax()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface'); 
                          
        $type = new AlphaNumeric();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
       
        $type->setOption('format','ccCC');
        $type->setOption('repeatMax',1);
        $type->setOption('repeatMin',2);
        $type->validate(); 
        
    }
    
}
/*End of file */

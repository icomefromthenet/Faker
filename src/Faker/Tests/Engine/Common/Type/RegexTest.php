<?php
namespace Faker\Tests\Engine\Common\Type;

use Faker\Components\Engine\Common\Type\Regex;
use Faker\Tests\Base\AbstractProject;


class RegexTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
         $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface'); 
            
        $type = new Regex();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Type\\TypeInterface',$type);
    
    }
    
    //--------------------------------------------------------------------------
    
    public function testConfig()
    {
         $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface'); 
            
        $type = new Regex();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('format','xxxx');

        $this->assertTrue($type->validate());
        
    }
    
    //------------------------------------------------------------------
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage  Error found STARTING at position 0 after `[` with msg Negated Character Set ranges not supported at this time
      */
    public function testConfigException()
    {
         $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface'); 
            
        $type = new Regex();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('format','[^a-z]');
        
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
        
            
        $type = new Regex();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('format','aaaa[0-9]{4}');
        $type->validate(); 
         
        $this->assertRegExp('/a{4}[0-9]{4}/',$type->generate(1,array()));
        $this->assertRegExp('/a{4}[0-9]{4}/',$type->generate(1,array()));
        $this->assertRegExp('/a{4}[0-9]{4}/',$type->generate(1,array()));
        $this->assertRegExp('/a{4}[0-9]{4}/',$type->generate(1,array()));
        $this->assertRegExp('/a{4}[0-9]{4}/',$type->generate(1,array()));
    }
    
}
/*End of file */

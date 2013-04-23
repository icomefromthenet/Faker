<?php
namespace Faker\Tests\Engine\Common\Type;

use Faker\Components\Engine\Common\Type\UniqueString;
use Faker\Components\Engine\Common\Type\UniqueNumber;
use Faker\Tests\Base\AbstractProject;

class UniqueTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
        
        $type = new UniqueString();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Type\\TypeInterface',$type);
    
        $type = new UniqueNumber();
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
        
        $type = new UniqueString();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('format', 'xxxx');
        $type->validate();        
        $this->assertSame('xxxx',$type->getOption('format'));
        
        $type = new UniqueNumber();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('format', 'xxxx');
        $type->validate();        
        $this->assertSame('xxxx',$type->getOption('format'));
        
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function testUniqueStringGenerate()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
                          
        $utilities->expects($this->once())
                   ->method('generateRandomAlphanumeric')
                   ->with($this->equalTo('ccCC'),$this->isInstanceOf($generator),$this->isInstanceOf($locale))
                   ->will($this->returnValue('dgHJ'));
       
        
        $type = new UniqueString();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('format','ccCC');
        $type->validate(); 
         
        $this->assertEquals('dgHJ',$type->generate(1,array()));
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    public function testUniqueNumberGenerate()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
                          
        $utilities->expects($this->once())
                   ->method('generateRandomNum')
                   ->with($this->equalTo('XXxx'),$this->isInstanceOf($generator))
                   ->will($this->returnValue(1207));
        
        $type = new UniqueNumber();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('format','XXxx');
        $type->validate(); 
         
        $this->assertEquals(1207,$type->generate(1,array()));
        
        
    }
    
    //  -------------------------------------------------------------------------
    
}
/* End of file */

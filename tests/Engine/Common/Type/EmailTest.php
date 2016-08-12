<?php
namespace Faker\Tests\Engine\Common\Type;

use Faker\Components\Engine\Common\Type\Email;
use Faker\Tests\Base\AbstractProject;

class EmailTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     

        $database  = $this->getProject()->getGeneratorDatabase();
            
        $type = new Email($database);
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

        $database  = $this->getProject()->getGeneratorDatabase();
            
        $type = new Email($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('format' ,'xxxx');
        $type->setOption('domains' , 'au,com.au');
        
        $this->assertTrue($type->validate());        
        $this->assertEquals('xxxx',$type->getOption('format'));
        $this->assertSame(array('au','com.au'),$type->getOption('domains'));
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage The child node "format" at path "config" must be configured
      */
    public function testConfigMissingFormat()
    {
       $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     

        $database  = $this->getProject()->getGeneratorDatabase();
            
        $type = new Email($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $this->assertTrue($type->validate());        
        
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     

        $database  = $this->getProject()->getGeneratorDatabase();
        
        $generator->expects($this->exactly(2))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->isType('integer'))
                  ->will($this->returnValue(0));
        
        $utilities->expects($this->exactly(2))
                   ->method('generateRandomAlphanumeric')
                   ->with($this->isType('string'),$this->isInstanceOf($generator),$this->isInstanceOf($locale))
                   ->will($this->onConsecutiveCalls('ddDDD','1111'));
        
        $type = new Email($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('format','{fname}\'{lname}{alpha2}@{alpha1}.{domain}');
        $type->setOption('params','{"alpha1":"ccCCC","alpha2":"xxxx"}');
        $type->validate(); 
        $values = array();  
        $this->assertEquals('Kristina\'Chung1111@ddDDD.edu',$type->generate(1,$values));
    }
    
    
}
/*End of file */

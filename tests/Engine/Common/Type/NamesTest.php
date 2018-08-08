<?php
namespace Faker\Tests\Engine\Common\Type;

use Faker\Components\Engine\Common\Type\Names;
use Faker\Tests\Base\AbstractProject;

class NamesTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');      

        $database  = $this->getProject()->getGeneratorDatabase();
            
        $type = new Names($database);
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
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');      

        $database  = $this->getProject()->getGeneratorDatabase();
        
        $type = new Names($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('format','xxxx');
        
        $this->assertTrue($type->validate());        
        $this->assertEquals('xxxx',$type->getOption('format'));
        
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
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');      

        $database  = $this->getProject()->getGeneratorDatabase();
            
        $type = new Names($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
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

        $database  = $this->getProject()->getGeneratorDatabase();
        
        $generator->expects($this->exactly(6))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->anything())
                  ->will($this->returnValue(0));
            
            
        $type = new Names($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('format','{fname} {lname}');
        
        $type->validate(); 
        
        $values = array();          
        $this->assertEquals('Kristina Chung',$type->generate(1,$values));
    
        $type->setOption('format','{fname} {inital} {lname}');
        $type->validate(); 
         
        $this->assertEquals('Kristina H Chung',$type->generate(1,$values));
    
        $type->setOption('format','{fname},{inital} {lname}');
        $type->validate(); 
         
        $this->assertEquals('Kristina,H Chung',$type->generate(1,$values));
        
        $type->setOption('format','{fname},{lname} {inital}');
        $type->validate(); 
         
        $this->assertEquals('Kristina,Chung H',$type->generate(1,$values));
        
        $type->setOption('format','{lname}');
        $type->validate(); 
         
        $this->assertEquals('Chung',$type->generate(1,$values));
        
        $type->setOption('format','{fname},{lname} {inital}');
        $type->validate();
        
        $this->assertEquals('Kristina,Chung H',$type->generate(1,$values));
         
    }
    
}
/*End of file */

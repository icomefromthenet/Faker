<?php
namespace Faker\Tests\Engine\Common\Type;

use Faker\Components\Engine\Common\Type\ConstantNumber;
use Faker\Components\Engine\Common\Type\ConstantString;
use Faker\Tests\Base\AbstractProject;


class ConstantTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     

        $type = new ConstantNumber();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Type\\TypeInterface',$type);
        
        $type = new ConstantString();
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
            
        $type = new ConstantString();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('value' ,'xxxx');
        $type->validate();        
        
        $this->assertEquals('xxxx',$type->getOption('value'));
        
        $type = new ConstantNumber();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('value','xxxx');
        $type->validate();
        
        $this->assertEquals('xxxx',$type->getOption('value'));
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage The child node "value" at path "config" must be configured
      */
    public function testConfigMissingValue()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');      
        
        $type = new ConstantString();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->validate();        
        
    }
   
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Constant::Type Option not in valid list
      */
    public function testConfigBadTypeOption()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');  
            
        $type = new ConstantString();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('value' , '1');
        $type->setOption('type' , 'none');
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
            
        $type = new ConstantString();
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        $type->setGenerator($generator);
        
        $type->setOption('value','ccCC');
        $type->validate();
        
        $values = array(); 
        $this->assertEquals('ccCC',$type->generate(1,$values));
        $this->assertEquals('ccCC',$type->generate(2,$values));
        $this->assertEquals('ccCC',$type->generate(3,$values));
            
        $type = new ConstantNumber();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('value','123');
        $type->validate(); 
        $this->assertEquals(123,$type->generate(1,$values));
        
        $type = new ConstantString();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);

        $type->setOption('value','1');
        $type->validate(); 
        $this->assertSame('1',$type->generate(1,$values));
       
       
    }
    
}
/* End of file */

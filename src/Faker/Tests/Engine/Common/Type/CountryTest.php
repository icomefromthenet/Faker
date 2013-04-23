<?php
namespace Faker\Tests\Engine\Common\Type;

use Faker\Components\Engine\Common\Type\Country,
    Faker\Tests\Base\AbstractProject;

class CountryTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     

        $database  = $this->getProject()->getGeneratorDatabase();
            
        $type = new Country($database);
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
            
        $type = new Country($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('countries' ,'AU,US,UK');
        $type->validate();        
        $this->assertSame(array('AU','US','UK'),$type->getOption('countries'));
        
        # test with min options
        $type = new Country($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->validate();        
        $this->assertSame($type->getOption('countries'),null);
   
    }
    
    //  -------------------------------------------------------------------------
    
    public function testGenerateRandomCountry()
    {
         $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     

        $database  = $this->getProject()->getGeneratorDatabase();
        
        $generator->expects($this->exactly(3))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(247))
                  ->will($this->returnValue(0));
            
            
        $type = new Country($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->validate(); 
         
        $this->assertEquals('Albania',$type->generate(1,array()));
        $this->assertEquals('Albania',$type->generate(1,array()));
        $this->assertEquals('Albania',$type->generate(1,array()));
       
    }
    
    
    public function testGenerateFromSingleCode()
    {
          $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     

        $database  = $this->getProject()->getGeneratorDatabase();
        
        $generator->expects($this->exactly(0))
                  ->method('generate');
            
            
        $type = new Country($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('countries','AU');
        $type->validate(); 
         
        $this->assertEquals('Australia',$type->generate(1,array()));
        $this->assertEquals('Australia',$type->generate(1,array()));
        $this->assertEquals('Australia',$type->generate(1,array()));
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException 
      */
    public function testGenerateMultipleCodesWithIncorrectCode()
    {
         $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     

        $database  = $this->getProject()->getGeneratorDatabase();
        
        $generator->expects($this->exactly(0))
                  ->method('generate');
            
            
        $type = new Country($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        
        $type->setOption('countries','AU,UK,US');
        $type->validate(); 
         
        $this->assertEquals('',$type->generate(1,array()));
    
        
    }
    
    public function testGenerateMultipleCodes()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     

        $database  = $this->getProject()->getGeneratorDatabase();
        
        $generator->expects($this->exactly(3))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(2))
                  ->will($this->returnValue(2));
            
            
        $type = new Country($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('countries','AU,GB,US');
        $type->validate(); 
         
        $this->assertEquals('United States',$type->generate(1,array()));
        $this->assertEquals('United States',$type->generate(1,array()));
        $this->assertEquals('United States',$type->generate(1,array()));
    
        
    }
    
}
/*End of file */

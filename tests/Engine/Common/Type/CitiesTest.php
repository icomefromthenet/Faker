<?php
namespace Faker\Tests\Engine\Common\Type;

use Faker\Components\Engine\Common\Type\Cities,
    Faker\Tests\Base\AbstractProject;

class CitiesTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     

        $database  = $this->getProject()->getGeneratorDatabase();
        
        $type = new Cities($database);
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
            
        $type = new Cities($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('countries' ,'AU,US,UK');
        $type->validate();        
        
        $this->assertSame($type->getOption('countries'),array('AU','US','UK'));
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
                  ->with($this->equalTo(0),$this->equalTo(130))
                  ->will($this->returnValue(20));
            
        $type = new Cities($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('countries','AU');
        $type->validate(); 
        $values = array(); 
        $this->assertStringMatchesFormat('Traralgon',$type->generate(1,$values));
        $this->assertStringMatchesFormat('Traralgon',$type->generate(2,$values));
        
    }
    
    public function testGenerateMaxRange()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     

        $database  = $this->getProject()->getGeneratorDatabase();
        
        $generator->expects($this->exactly(2))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(130))
                  ->will($this->returnValue(130));
            
        $type = new Cities($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('countries','AU');
        $type->validate(); 
        
        $values = array();         
        $this->assertStringMatchesFormat('St Albans',$type->generate(1,$values));
        $this->assertStringMatchesFormat('St Albans',$type->generate(2,$values));
        
    }
    
    public function testGenerateMinRange()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     

        $database  = $this->getProject()->getGeneratorDatabase();
        
        $generator->expects($this->exactly(2))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(130))
                  ->will($this->returnValue(1));
            
        $type = new Cities($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('countries','AU');
        $type->validate(); 
        $values = array(); 
        $this->assertStringMatchesFormat('Roebourne',$type->generate(1,$values));
        $this->assertStringMatchesFormat('Roebourne',$type->generate(2,$values));
        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Cities::no cities found for countries ASASAASAAU
      */
    public function testExceptionBadCityCode()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');     

        $database  = $this->getProject()->getGeneratorDatabase();
        
        $generator->expects($this->exactly(0))
                  ->method('generate');
            
        $type = new Cities($database);
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('countries','ASASAASAAU');
        $type->validate(); 
        $values = array(); 
        $type->generate(1,$values);
        
    }
    
    
}
/*End of file */

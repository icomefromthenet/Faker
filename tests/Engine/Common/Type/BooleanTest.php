<?php
namespace Faker\Tests\Engine\Common\Type;

use Faker\Components\Engine\Common\Type\BooleanType,
    Faker\Tests\Base\AbstractProject;

    
class BooleanTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     

            
        $type = new BooleanType();
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

            
        $type = new BooleanType();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('value' , true);
        $this->assertTrue($type->validate());        
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     

            
        $type = new BooleanType();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('value',true);
        $type->validate(); 
        
        $values = array();         
        $this->assertEquals(true,$type->generate(1,$values));
        
        $type = new BooleanType();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        
        $type->setOption('value',false);
        $type->validate(); 

        $this->assertEquals(false,$type->generate(1,$values));
    
    }
    
}
/*End of file */

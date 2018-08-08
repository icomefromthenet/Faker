<?php
namespace Faker\Tests\Engine\Common\Type;

use Faker\Components\Engine\Common\Type\ClosureType;
use Faker\Tests\Base\AbstractProject;

class ClosureTypeTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');   
            
        $type = new ClosureType();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        $type->setClosure(function($rows,$values){});
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Type\\TypeInterface',$type);
    
    }
    
    //--------------------------------------------------------------------------
    
    public function testDefaultConfig()
    {
         $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
            
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');   
            
        $type = new ClosureType();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        $type->setClosure(function($rows,$values){});
        
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
        
            
        $type = new ClosureType();
        $type->setGenerator($generator);
        $type->setLocale($locale);
        $type->setUtilities($utilities);
        $type->setClosure(function($rows,$values){ return 5;});
        
       
        $type->validate(); 
        $values = array();          
        $this->assertEquals(5,$type->generate(1,$values));
        $this->assertEquals(5,$type->generate(1,$values));
        $this->assertEquals(5,$type->generate(1,$values));
        
    }
    
    
    
}
/*End of file */

<?php
namespace Faker\Tests\Faker\Type;

use Faker\Components\Faker\Type\AlphaNumeric,
    Faker\Tests\Base\AbstractProject;

class AlphaNumericTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();

        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
            
        $type = new AlphaNumeric($id,$parent,$event,$utilities,$generator);
        
        $this->assertInstanceOf('\\Faker\\Components\\Faker\\TypeInterface',$type);
    
    }
    
    //--------------------------------------------------------------------------
    
    public function testConfig()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
                      
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
            
        $type = new AlphaNumeric($id,$parent,$event,$utilities,$generator);
        $type->setOption('format','xxxx');
        $type->setOption('name','alphanumeric');
        $type->merge();        
    }
    
    
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $utilities->expects($this->once())
                   ->method('generateRandomAlphanumeric')
                   ->with($this->equalTo('ccCC'))
                   ->will($this->returnValue('dgHJ'));
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
                      
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
        $locale  = $this->getMock('\Faker\Locale\LocaleInterface');
        
            
        $type = new AlphaNumeric($id,$parent,$event,$utilities,$generator);
        $type->setOption('format','ccCC');
        $type->setOption('name','alphanumeric');
        $type->setLocale($locale);
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('dgHJ',$type->generate(1,array()));
    }
    
}
/*End of file */

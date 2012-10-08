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
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
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
                      
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
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
                      
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale  = $this->getMock('\Faker\Locale\LocaleInterface');
        
            
        $type = new AlphaNumeric($id,$parent,$event,$utilities,$generator);
        $type->setOption('format','ccCC');
        $type->setOption('name','alphanumeric');
        $type->setLocale($locale);
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('dgHJ',$type->generate(1,array()));
    }
    
    public function testGenerateWithEqualRepeat()
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
                      
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale  = $this->getMock('\Faker\Locale\LocaleInterface');
        
            
        $type = new AlphaNumeric($id,$parent,$event,$utilities,$generator);
        $type->setOption('format','ccCC');
        $type->setOption('name','alphanumeric');
        $type->setOption('repeatMax',1);
        $type->setOption('repeatMin',1);
        $type->setLocale($locale);
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('dgHJ',$type->generate(1,array()));
    }
    
     public function testGenerateWithNotEqualRepeat()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $utilities->expects($this->any())
                   ->method('generateRandomAlphanumeric')
                   ->with($this->equalTo('ccCC'))
                   ->will($this->returnValue('dgHJ'));
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
                      
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale  = $this->getMock('\Faker\Locale\LocaleInterface');
        
        $generator->expects($this->exactly(3))
                  ->method('generate')
                  ->with($this->equalTo(1),$this->equalTo(5))
                  ->will($this->onConsecutiveCalls(1, 2, 3));
        
            
        $type = new AlphaNumeric($id,$parent,$event,$utilities,$generator);
        $type->setOption('format','ccCC');
        $type->setOption('name','alphanumeric');
        $type->setOption('repeatMax',5);
        $type->setOption('repeatMin',1);
        $type->setLocale($locale);
        $type->merge();
        $type->validate(); 
         
        $length = strlen($type->generate(1,array())); 
        $this->assertGreaterThanOrEqual(4, $length);
        $this->assertLessThanOrEqual(20, $length);

        
        $length = strlen($type->generate(2,array())); 
        $this->assertGreaterThanOrEqual(8, $length);
        $this->assertLessThanOrEqual(20, $length);

        
        $length = strlen($type->generate(3,array())); 
        $this->assertGreaterThanOrEqual(12, $length);
        $this->assertLessThanOrEqual(20, $length);

        
    }
    
}
/*End of file */

<?php
namespace Faker\Tests\Faker\Type;

use Faker\Components\Faker\Type\UniqueString,
    Faker\Components\Faker\Type\UniqueNumber,
    Faker\Tests\Base\AbstractProject;

class UniqueTest extends AbstractProject
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
        
        $type = new UniqueString($id,$parent,$event,$utilities,$generator);
        $this->assertInstanceOf('\\Faker\\Components\\Faker\\TypeInterface',$type);
    
        $type = new UniqueNumber($id,$parent,$event,$utilities,$generator);
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
        
        $type = new UniqueString($id,$parent,$event,$utilities,$generator);
        $type->setOption('format', 'xxxx');
        $type->merge();        
        $this->assertSame('xxxx',$type->getOption('format'));
        
        $type = new UniqueNumber($id,$parent,$event,$utilities,$generator);
        $type->setOption('format', 'xxxx');
        $type->merge();        
        $this->assertSame('xxxx',$type->getOption('format'));
        
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function testUniqueStringGenerate()
    {
        $id = 'table_two';
        
        $locale   = $this->getMock('\Faker\Locale\LocaleInterface');
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');    
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $utilities->expects($this->once())
                   ->method('generateRandomAlphanumeric')
                   ->with($this->equalTo('ccCC'),$this->equalTo($generator),$this->equalTo($locale))
                   ->will($this->returnValue('dgHJ'));
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                       ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $type = new UniqueString($id,$parent,$event,$utilities,$generator);
        $type->setOption('format','ccCC');
        $type->setLocale($locale);
        $type->merge();        
        $type->validate(); 
         
        $this->assertEquals('dgHJ',$type->generate(1,array()));
        
        
    }
    
    //  -------------------------------------------------------------------------
    
     public function testUniqueNumberGenerate()
    {
        $id = 'table_two';
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $utilities->expects($this->once())
                   ->method('generateRandomNum')
                   ->with($this->equalTo('XXxx'),$this->equalTo($generator))
                   ->will($this->returnValue(1207));
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $type = new UniqueNumber($id,$parent,$event,$utilities,$generator);
        $type->setOption('format','XXxx');
        $type->merge(); 
        $type->validate(); 
         
        $this->assertEquals(1207,$type->generate(1,array()));
        
        
    }
    
    //  -------------------------------------------------------------------------
    
}
/*End of file */

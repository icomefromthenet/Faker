<?php
namespace Faker\Tests\Faker\Type;

use Faker\Components\Faker\Type\Email,
    Faker\Components\Faker\Utilities,
    Faker\Tests\Base\AbstractProject;    

class EmailTest extends AbstractProject
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
            
        $type = new Email($id,$parent,$event,$utilities,$generator);
        $type->setOption('name','email');
        
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
            
        $type = new Email($id,$parent,$event,$utilities,$generator);
        $type->setOption('format' ,'xxxx');
        $type->setOption('domains' , 'au,com.au');
        $type->setOption('name','email');
        $type->merge();        
        
        $this->assertEquals('xxxx',$type->getOption('format'));
        $this->assertSame(array('au','com.au'),$type->getOption('domains'));
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage The child node "format" at path "config" must be configured
      */
    public function testConfigMissingFormat()
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
            
        $type = new Email($id,$parent,$event,$utilities,$generator);
        $type->setOption('name','email');
        $type->merge();        
        
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
        $id = 'table_two';
        $project = $this->getProject();
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent    = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $generator->expects($this->exactly(2))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->isType('integer'))
                  ->will($this->returnValue(0));
        
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');    
        
        $utilities->expects($this->exactly(2))
                   ->method('generateRandomAlphanumeric')
                   ->with($this->isType('string'),$this->isInstanceOf($generator),$this->isInstanceOf($locale))
                   ->will($this->onConsecutiveCalls('ddDDD','1111'));
        
        $utilities->expects($this->once())
                  ->method('getGeneratorDatabase')
                  ->will($this->returnValue($project['faker_database']));
            
        $type = new Email($id,$parent,$event,$utilities,$generator);
        $type->setOption('format','{fname}\'{lname}{alpha2}@{alpha1}.{domain}');
        $type->setOption('params','{"alpha1":"ccCCC","alpha2":"xxxx"}');
        $type->setOption('name','email');
        $type->setLocale($locale);
        $type->merge();       
        $type->validate(); 
         
        $this->assertEquals('Kristina\'Chung1111@ddDDD.edu',$type->generate(1,array()));
    }
    
    
}
/*End of file */

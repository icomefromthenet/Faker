<?php
namespace Faker\Tests\Faker\Type;

use Faker\Components\Faker\Type\Regex,
    Faker\Tests\Base\AbstractProject;

class RegexTest extends AbstractProject
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
            
        $type = new Regex($id,$parent,$event,$utilities,$generator);
        
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
            
        $type = new Regex($id,$parent,$event,$utilities,$generator);
        $type->setOption('format','xxxx');
        $type->setOption('name','regex');
        $type->merge();
        
    }
    
    //------------------------------------------------------------------
    
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage  Error found STARTING at position 0 after `[` with msg Negated Character Set ranges not supported at this time
      */
    public function testConfigException()
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
            
        $type = new Regex($id,$parent,$event,$utilities,$generator);
        $type->setOption('format','[^a-z]');
        $type->setOption('name','regex');
        $type->merge();
        $type->validate();
        
    }
    
    
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
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
        $locale  = $this->getMock('\Faker\Locale\LocaleInterface');
        
            
        $type = new Regex($id,$parent,$event,$utilities,$generator);
        $type->setOption('format','aaaa[0-9]{4}');
        $type->setOption('name','alphanumeric');
        $type->setLocale($locale);
        $type->merge();
        $type->validate(); 
         
        $this->assertRegExp('/a{4}[0-9]{4}/',$type->generate(1,array()));
        $this->assertRegExp('/a{4}[0-9]{4}/',$type->generate(1,array()));
        $this->assertRegExp('/a{4}[0-9]{4}/',$type->generate(1,array()));
        $this->assertRegExp('/a{4}[0-9]{4}/',$type->generate(1,array()));
        $this->assertRegExp('/a{4}[0-9]{4}/',$type->generate(1,array()));
    }
    
}
/*End of file */

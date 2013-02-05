<?php
namespace Faker\Tests\Engine\Original\Type;

use Faker\Components\Engine\Original\Type\Country,
    Faker\Components\Engine\Original\Utilities,
    Faker\Tests\Base\AbstractProject;

class CountryTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Original\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();

        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $type = new Country($id,$parent,$event,$utilities,$generator);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Original\\TypeInterface',$type);
    
    }
    
    //--------------------------------------------------------------------------
    
    public function testConfig()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Original\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
            
        $type = new Country($id,$parent,$event,$utilities,$generator);
        $type->setOption('countries' ,'AU,US,UK');
        $type->setOption('name','country');
        $type->merge();        
        $this->assertSame(array('AU','US','UK'),$type->getOption('countries'));
        
        # test with min options
        $type = new Country($id,$parent,$event,$utilities,$generator);
        $type->setOption('name','country');
        $type->merge();        
        $this->assertSame($type->getOption('countries'),null);
   
    }
    
    //  -------------------------------------------------------------------------
    
    public function testGenerateRandomCountry()
    {
        $id = 'table_two';
        $project = $this->getProject();
       
        $utilities = new Utilities($project);
        
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $generator->expects($this->exactly(3))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(247))
                  ->will($this->returnValue(0));
            
            
        $type = new Country($id,$parent,$event,$utilities,$generator);
        $type->setOption('name','country');
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('Albania',$type->generate(1,array()));
        $this->assertEquals('Albania',$type->generate(1,array()));
        $this->assertEquals('Albania',$type->generate(1,array()));
       
    }
    
    
    public function testGenerateFromSingleCode()
    {
        $id = 'table_two';
        $project = $this->getProject();
       
        $utilities = new Utilities($project);
        
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $generator->expects($this->exactly(0))
                  ->method('generate');
            
            
        $type = new Country($id,$parent,$event,$utilities,$generator);
        $type->setOption('countries','AU');
        $type->setOption('name','country');
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('Australia',$type->generate(1,array()));
        $this->assertEquals('Australia',$type->generate(1,array()));
        $this->assertEquals('Australia',$type->generate(1,array()));
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Original\Exception 
      */
    public function testGenerateMultipleCodesWithIncorrectCode()
    {
        $id = 'table_two';
        $project = $this->getProject();
       
        $utilities = new Utilities($project);
        
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $generator->expects($this->exactly(0))
                  ->method('generate');
            
            
        $type = new Country($id,$parent,$event,$utilities,$generator);
        $type->setOption('countries','AU,UK,US');
        $type->setOption('name','country');
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('',$type->generate(1,array()));
    
        
    }
    
    public function testGenerateMultipleCodes()
    {
        $id = 'table_two';
        $project = $this->getProject();
       
        $utilities = new Utilities($project);
        
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $generator->expects($this->exactly(3))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(2))
                  ->will($this->returnValue(2));
            
            
        $type = new Country($id,$parent,$event,$utilities,$generator);
        $type->setOption('countries','AU,GB,US');
        $type->setOption('name','country');
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('United States',$type->generate(1,array()));
        $this->assertEquals('United States',$type->generate(1,array()));
        $this->assertEquals('United States',$type->generate(1,array()));
    
        
    }
    
}
/*End of file */

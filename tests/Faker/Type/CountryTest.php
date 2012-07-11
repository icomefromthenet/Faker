<?php
namespace Faker\Tests\Faker\Type;

use Faker\Components\Faker\Type\Country,
    Faker\Components\Faker\Utilities,
    Faker\Tests\Base\AbstractProject;

class CountryTest extends AbstractProject
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
            
        $type = new Country($id,$parent,$event,$utilities,$generator);
        
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
            
        $type = new Country($id,$parent,$event,$utilities,$generator);
        $type->setOption('countries' ,'AU,US,UK');
        $type->merge();        
        $this->assertSame(array('AU','US','UK'),$type->getOption('countries'));
        
        # test with no options
        $type = new Country($id,$parent,$event,$utilities,$generator);
        $type->merge();        
        $this->assertSame($type->getOption('countries'),null);
   
    }
    
    //  -------------------------------------------------------------------------
    
    public function testGenerateRandomCountry()
    {
        $id = 'table_two';
        $project = $this->getProject();
       
        $utilities = new Utilities($project);
        
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
        $generator->expects($this->exactly(3))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(248))
                  ->will($this->returnValue(0));
            
            
        $type = new Country($id,$parent,$event,$utilities,$generator);
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('Afghanistan',$type->generate(1,array()));
        $this->assertEquals('Afghanistan',$type->generate(1,array()));
        $this->assertEquals('Afghanistan',$type->generate(1,array()));
       
    }
    
    
    public function testGenerateFromSingleCode()
    {
        $id = 'table_two';
        $project = $this->getProject();
       
        $utilities = new Utilities($project);
        
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
        $generator->expects($this->exactly(0))
                  ->method('generate');
            
            
        $type = new Country($id,$parent,$event,$utilities,$generator);
        $type->setOption('countries','AU');
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('Australia',$type->generate(1,array()));
        $this->assertEquals('Australia',$type->generate(1,array()));
        $this->assertEquals('Australia',$type->generate(1,array()));
    }
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception 
      */
    public function testGenerateMultipleCodesWithIncorrectCode()
    {
        $id = 'table_two';
        $project = $this->getProject();
       
        $utilities = new Utilities($project);
        
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
        $generator->expects($this->exactly(0))
                  ->method('generate');
            
            
        $type = new Country($id,$parent,$event,$utilities,$generator);
        $type->setOption('countries','AU,UK,US');
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('',$type->generate(1,array()));
    
        
    }
    
    public function testGenerateMultipleCodes()
    {
        $id = 'table_two';
        $project = $this->getProject();
       
        $utilities = new Utilities($project);
        
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
        $generator->expects($this->exactly(3))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(2))
                  ->will($this->returnValue(2));
            
            
        $type = new Country($id,$parent,$event,$utilities,$generator);
        $type->setOption('countries','AU,GB,US');
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('United States',$type->generate(1,array()));
        $this->assertEquals('United States',$type->generate(1,array()));
        $this->assertEquals('United States',$type->generate(1,array()));
    
        
    }
    
}
/*End of file */

<?php
namespace Faker\Tests\Engine\Original\Type;

use Faker\Components\Engine\Original\Type\Cities,
    Faker\Components\Engine\Original\Utilities,
    Faker\Tests\Base\AbstractProject;

class CitiesTest extends AbstractProject
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
            
        $type = new Cities($id,$parent,$event,$utilities,$generator);
        
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
            
        $type = new Cities($id,$parent,$event,$utilities,$generator);
        $type->setOption('countries' ,'AU,US,UK');
        $type->setOption('name','cities');
        $type->merge();        
        
        $this->assertSame($type->getOption('countries'),array('AU','US','UK'));
    }
    
    //  -------------------------------------------------------------------------
    
    public function testGenerate()
    {
        $id = 'table_two';
        $project = $this->getProject();
       
        $utilities = new Utilities($project);
        
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        
        $generator->expects($this->exactly(2))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(130))
                  ->will($this->returnValue(20));
            
        $type = new Cities($id,$parent,$event,$utilities,$generator);
        $type->setOption('countries','AU');
        $type->setOption('name','cities');
        $type->merge();
        $type->validate(); 
        
        $this->assertStringMatchesFormat('Traralgon',$type->generate(1,array()));
        $this->assertStringMatchesFormat('Traralgon',$type->generate(2,array()));
        
    }
    
    public function testGenerateMaxRange()
    {
        $id = 'table_two';
        $project = $this->getProject();
       
        $utilities = new Utilities($project);
        
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        
        $generator->expects($this->exactly(2))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(130))
                  ->will($this->returnValue(130));
            
        $type = new Cities($id,$parent,$event,$utilities,$generator);
        $type->setOption('countries','AU');
        $type->setOption('name','cities');
        $type->merge();
        $type->validate(); 
        
        $this->assertStringMatchesFormat('St Albans',$type->generate(1,array()));
        $this->assertStringMatchesFormat('St Albans',$type->generate(2,array()));
        
    }
    
    public function testGenerateMinRange()
    {
        $id = 'table_two';
        $project = $this->getProject();
       
        $utilities = new Utilities($project);
        
        
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        
        $generator->expects($this->exactly(2))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->equalTo(130))
                  ->will($this->returnValue(1));
            
        $type = new Cities($id,$parent,$event,$utilities,$generator);
        $type->setOption('countries','AU');
        $type->setOption('name','cities');
        $type->merge();
        $type->validate(); 
        
        $this->assertStringMatchesFormat('Roebourne',$type->generate(1,array()));
        $this->assertStringMatchesFormat('Roebourne',$type->generate(2,array()));
        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\Original\Exception
      *  @expectedExceptionMessage Cities::no cities found for countries ASASAASAAU
      */
    public function testExceptionBadCityCode()
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
            
        $type = new Cities($id,$parent,$event,$utilities,$generator);
        $type->setOption('countries','ASASAASAAU');
        $type->setOption('name','cities');
        $type->merge();
        $type->validate(); 
        
        $type->generate(1,array());
        
    }
    
    
}
/*End of file */

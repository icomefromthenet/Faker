<?php
namespace Faker\Tests\Faker\Type;

use Faker\Components\Faker\Type\Cities,
    Faker\Components\Faker\Utilities,
    Faker\Tests\Base\AbstractProject;

class CitiesTest extends AbstractProject
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
            
        $type = new Cities($id,$parent,$event,$utilities,$generator);
        
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
            
        $type = new Cities($id,$parent,$event,$utilities,$generator);
        $type->setOption('countries' ,'AU,US,UK');
        $type->merge();        
        
        $this->assertSame($type->getOption('countries'),array('AU','US','UK'));
    }
    
    //  -------------------------------------------------------------------------
    
    public function testGenerate()
    {
        $id = 'table_two';
        $project = $this->getProject();
       
        $utilities = new Utilities($project);
        
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
            
        $type = new Cities($id,$parent,$event,$utilities,$generator);
        $type->setOption('countries','AU');
        $type->merge();
        $type->validate(); 
         
        $value = $type->generate(1,array());
        $this->assertStringMatchesFormat('%s',$value);
        
        $value = $type->generate(1,array());
        $this->assertStringMatchesFormat('%s',$value);
       
        $value = $type->generate(1,array());
        $this->assertStringMatchesFormat('%s',$value);
   
    }
    
    
}
/*End of file */

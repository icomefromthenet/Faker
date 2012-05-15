<?php
namespace Faker\Tests\Faker\Type;

use Faker\Components\Faker\Type\Country,
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
      
            
        $type = new Country($id,$parent,$event,$utilities);
        
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
            
        $type = new Country($id,$parent,$event,$utilities);
        $config = array('countries' =>'AU,US,UK');
        $options = $type->merge($config);        
        $this->assertSame($options['countries'],array('AU','US','UK'));
        
        # test with no options
        $type = new Country($id,$parent,$event,$utilities);
        $options = $type->merge(array());        
        $this->assertSame($options['countries'],null);
   
    }
    
    //  -------------------------------------------------------------------------
    
    public function testGenerate()
    {
        $id = 'table_two';
        $project = $this->getProject();
       
        $utilities = new Faker\Components\Faker\Utilities($project);
        
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Country($id,$parent,$event,$utilities);
        $type->setOption('countries','AU,UK,US');
        $type->validate(); 
         
        $value = $type->generate(1,array());
        $this->assertStringMatchesFormat('%s',$value);
        $value = $type->generate(1,array());
        $this->assertStringMatchesFormat('%s',$value);
        $value = $type->generate(1,array());
        $this->assertStringMatchesFormat('%s',$value);
        
        # test with no options        
        $type = new Country($id,$parent,$event,$utilities);
        $type->validate(); 
                
        $value = $type->generate(1,array());
        $this->assertStringMatchesFormat('%s',$value);
        $value = $type->generate(1,array());
        $this->assertStringMatchesFormat('%s',$value);
        $value = $type->generate(1,array());
        $this->assertStringMatchesFormat('%s',$value);
        $value = $type->generate(1,array());
        $this->assertStringMatchesFormat('%s',$value);
   
    }
    
    
}
/*End of file */

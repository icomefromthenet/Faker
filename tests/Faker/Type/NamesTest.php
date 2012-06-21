<?php
namespace Faker\Tests\Faker\Type;

use Faker\Components\Faker\Type\Names,
    Faker\Components\Faker\Utilities,
    Faker\Tests\Base\AbstractProject;

class NamesTest extends AbstractProject
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
      
            
        $type = new Names($id,$parent,$event,$utilities);
        
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
            
        $type = new Names($id,$parent,$event,$utilities);
        $type->setOption('format','xxxx'); 
        $type->merge();        
        $this->assertEquals('xxxx',$type->getOption('format'));
        
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
            
        $type = new Names($id,$parent,$event,$utilities);
        $type->merge();        
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
            
        $type = new Names($id,$parent,$event,$utilities);
        $type->setOption('format','{fname} {lname}');
        $type->merge();
        $type->validate(); 
         
        $this->assertStringMatchesFormat('%s %s',$type->generate(1,array()));
    
    
        $type->setOption('format','{fname} {inital} {lname}');
        $type->merge();
        $type->validate(); 
         
        $this->assertStringMatchesFormat('%s %s %s',$type->generate(1,array()));
    
        $type->setOption('format','{fname},{inital} {lname}');
        $type->merge();
        $type->validate(); 
         
        $this->assertStringMatchesFormat('%s,%s %s',$type->generate(1,array()));
        
        $type->setOption('format','{fname},{lname} {inital}');
        $type->merge();
        $type->validate(); 
         
        $this->assertStringMatchesFormat('%s,%s %s',$type->generate(1,array()));
        
        $type->setOption('format','{lname}');
        $type->merge();
        $type->validate(); 
         
        $this->assertStringMatchesFormat('%s',$type->generate(1,array()));
        
        $type->setOption('format','{fname},{lname} {inital}');
        $type->merge();
        $type->validate(); 
         
    }
    
}
/*End of file */

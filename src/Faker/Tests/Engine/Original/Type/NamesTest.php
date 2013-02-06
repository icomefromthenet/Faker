<?php
namespace Faker\Tests\Engine\Original\Type;

use Faker\Components\Engine\Original\Type\Names,
    Faker\Components\Engine\Original\Utilities,
    Faker\Tests\Base\AbstractProject;

class NamesTest extends AbstractProject
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
            
        $type = new Names($id,$parent,$event,$utilities,$generator);
        
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
        
        $type = new Names($id,$parent,$event,$utilities,$generator);
        $type->setOption('format','xxxx');
        $type->setOption('name','names');
        $type->merge();        
        $this->assertEquals('xxxx',$type->getOption('format'));
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Engine\Original\Exception
      *  @expectedExceptionMessage The child node "format" at path "config" must be configured
      */
    public function testConfigMissingFormat()
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
            
        $type = new Names($id,$parent,$event,$utilities,$generator);
        $type->setOption('name','names');
        $type->merge();        
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
        $generator->expects($this->exactly(6))
                  ->method('generate')
                  ->with($this->equalTo(0),$this->anything())
                  ->will($this->returnValue(0));
            
            
        $type = new Names($id,$parent,$event,$utilities,$generator);
        $type->setOption('format','{fname} {lname}');
        $type->setOption('name','names');
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('Kristina Chung',$type->generate(1,array()));
    
    
        $type->setOption('format','{fname} {inital} {lname}');
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('Kristina H Chung',$type->generate(1,array()));
    
        $type->setOption('format','{fname},{inital} {lname}');
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('Kristina,H Chung',$type->generate(1,array()));
        
        $type->setOption('format','{fname},{lname} {inital}');
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('Kristina,Chung H',$type->generate(1,array()));
        
        $type->setOption('format','{lname}');
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('Chung',$type->generate(1,array()));
        
        $type->setOption('format','{fname},{lname} {inital}');
        $type->merge();
        $type->validate();
        
        $this->assertEquals('Kristina,Chung H',$type->generate(1,array()));
         
    }
    
}
/*End of file */
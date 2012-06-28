<?php
namespace Faker\Tests\Faker\Type;

use Faker\Components\Faker\Type\Numeric,
    Faker\Tests\Base\AbstractProject;

class NumericTest extends AbstractProject
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
            
        $type = new Numeric($id,$parent,$event,$utilities,$generator);
        
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
            
        $type = new Numeric($id,$parent,$event,$utilities,$generator);
        $type->setOption('format' ,'xxxx'); 
        $type->merge();        
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
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
            
        $type = new Numeric($id,$parent,$event,$utilities,$generator);
        $type->merge();        
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $utilities->expects($this->once())
                   ->method('generateRandomNum')
                   ->with($this->equalTo('xxxx'))
                   ->will($this->returnValue(1234));
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
            
        $type = new Numeric($id,$parent,$event,$utilities,$generator);
        $type->setOption('format','xxxx');
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals(1234,$type->generate(1,array()));
    }
    
    public function testGenerateWithDecimal()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $utilities->expects($this->once())
                   ->method('generateRandomNum')
                   ->with($this->equalTo('xxxx.xx'))
                   ->will($this->returnValue(1234.22));
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
            
        $type = new Numeric($id,$parent,$event,$utilities,$generator);
        $type->setOption('format','xxxx.xx');
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals(1234.22,$type->generate(1,array()));
    }
}
/*End of file */

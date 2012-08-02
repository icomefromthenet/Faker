<?php
namespace Faker\Tests\Faker\Type;

use Faker\Components\Faker\Type\ConstantNumber,
    Faker\Components\Faker\Type\ConstantString,
    Faker\Components\Faker\Utilities,
    Faker\Tests\Base\AbstractProject;


class ConstantTest extends AbstractProject
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
        
        $type = new ConstantNumber($id,$parent,$event,$utilities,$generator);
        
        $this->assertInstanceOf('\\Faker\\Components\\Faker\\TypeInterface',$type);
        
        $type = new ConstantString($id,$parent,$event,$utilities,$generator);
        
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
            
        $type = new ConstantString($id,$parent,$event,$utilities,$generator);
        $type->setOption('value' ,'xxxx');
        $type->setOption('name','constant_string');
        $type->merge();        
        
        $this->assertEquals('xxxx',$type->getOption('value'));
        
        $type = new ConstantNumber($id,$parent,$event,$utilities,$generator);
        $type->setOption('value','xxxx');
        $type->setOption('name','constant_number');
        $type->merge();
        
        $this->assertEquals('xxxx',$type->getOption('value'));
        
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage The child node "value" at path "config" must be configured
      */
    public function testConfigMissingValue()
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
        
        $type = new ConstantString($id,$parent,$event,$utilities,$generator);
        $type->setOption('name','constant_string');
        $type->merge();        
        
    }
   
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Constant::Type Option not in valid list
      */
    public function testConfigBadTypeOption()
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
            
        $type = new ConstantString($id,$parent,$event,$utilities,$generator);
        $type->setOption('value' , '1');
        $type->setOption('type' , 'none');
        $type->setOption('name','constant_string');
        $type->merge();        
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
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
            
        $type = new ConstantString($id,$parent,$event,$utilities,$generator);
        $type->setOption('value','ccCC');
        $type->setOption('name','constant_string');
        $type->merge();
        $type->validate(); 
         
        $this->assertEquals('ccCC',$type->generate(1,array()));
        $this->assertEquals('ccCC',$type->generate(2,array()));
        $this->assertEquals('ccCC',$type->generate(3,array()));
            
        $type = new ConstantNumber($id,$parent,$event,$utilities,$generator);
        $type->setOption('value','123');
        $type->setOption('name','constant_number');
        $type->merge();
        $type->validate(); 
        $this->assertEquals(123,$type->generate(1,array()));
        
        $type = new ConstantString($id,$parent,$event,$utilities,$generator);
        $type->setOption('value','1');
        $type->setOption('name','constant_string');
        $type->merge();
        $type->validate(); 
        $this->assertSame('1',$type->generate(1,array()));
       
       
    }
    
}
/*End of file */

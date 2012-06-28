<?php
namespace Faker\Tests\Faker\Type;

use Faker\Components\Faker\Type\Text,
    Faker\Tests\Base\AbstractProject;

class TextTest extends AbstractProject
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
            
        $type = new Text($id,$parent,$event,$utilities,$generator);
        
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
            
        $type = new Text($id,$parent,$event,$utilities,$generator);
        $type->setOption('paragraphs',5);
        $type->setOption('maxlines' ,5);
        $type->setOption('minlines' ,1);
        $type->merge();        
        
        $this->assertSame(5,$type->getOption('paragraphs'));
        $this->assertSame(5,$type->getOption('maxlines'));
        $this->assertSame(1,$type->getOption('minlines'));
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Numeric::Paragraphs must be and integer
      */
    public function testConfigParagraphsNotInteger()
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
            
        $type = new Text($id,$parent,$event,$utilities,$generator);
        $type->setOption('paragraphs','aaa');
        $type->setOption('maxlines' ,5);
        $type->setOption('minlines' ,1);
        $type->merge();        
        
        
    }
   
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Numeric::minlines must be and integer
      */
    public function testConfigMinLinesNotInteger()
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
            
        $type = new Text($id,$parent,$event,$utilities,$generator);
        $type->setOption('paragraphs',1);
        $type->setOption('maxlines' ,5);
        $type->setOption('minlines' ,'aaa');
        $type->merge();        
        
        
    }
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Numeric::maxlines must be and integer
      */
    public function testConfigMaxLinesNotInteger()
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
            
        $type = new Text($id,$parent,$event,$utilities,$generator);
        $type->setOption('paragraphs',1);
        $type->setOption('maxlines' ,'aaa');
        $type->setOption('minlines' ,1);
        $type->merge();        
        
        
    }
     
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
        $id = 'table_two';
        
        $generator = $this->getMock('\Faker\Generator\GeneratorInterface');
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $utilities->expects($this->exactly(5))
                   ->method('generateRandomText')
                   ->with($this->isType('array'),$this->equalTo(5),$this->equalTo(30),$this->equalTo($generator))
                   ->will($this->returnValue('dgHJ'));
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
        
            
        $type = new Text($id,$parent,$event,$utilities,$generator);
        $type->setOption('paragraphs',5);
        $type->setOption('maxlines',30);
        $type->setOption('minlines',5);
        $type->merge();
        $type->validate(); 
         
        $type->generate(1,array());
    }
    
}
/*End of file */

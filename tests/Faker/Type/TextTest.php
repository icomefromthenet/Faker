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
      
            
        $type = new Text($id,$parent,$event,$utilities);
        
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
            
        $type = new Text($id,$parent,$event,$utilities);
        $config = array('paragraphs' => 5, 'maxlines' => 5 , 'minlines' => 1);
        
        
        $options = $type->merge($config);        
        
        $this->assertSame($options['paragraphs'],$config['paragraphs']);
        $this->assertSame($options['maxlines'],$config['maxlines']);
        $this->assertSame($options['minlines'],$config['minlines']);
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
            
        $type = new Text($id,$parent,$event,$utilities);
        $config = array('paragraphs' => 'aaaa', 'maxlines' => 5 , 'minlines' => 1);
        
        $options = $type->merge($config);        
        
        
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
            
        $type = new Text($id,$parent,$event,$utilities);
        $config = array('paragraphs' => 1, 'maxlines' => 5 , 'minlines' => 'aa');
        
        $options = $type->merge($config);        
        
        
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
            
        $type = new Text($id,$parent,$event,$utilities);
        $config = array('paragraphs' => 1, 'maxlines' => 'aaa' , 'minlines' => 1);
        
        $options = $type->merge($config);        
        
        
    }
     
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $utilities->expects($this->exactly(5))
                   ->method('generateRandomText')
                   ->with($this->isType('array'),$this->equalTo(true),$this->equalTo(5),$this->equalTo(30))
                   ->will($this->returnValue('dgHJ'));
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Text($id,$parent,$event,$utilities);
        $type->setOption('paragraphs',5);
        $type->setOption('maxlines',30);
        $type->setOption('minlines',5);
        $type->validate(); 
         
        $type->generate(1,array());
    }
    
}
/*End of file */

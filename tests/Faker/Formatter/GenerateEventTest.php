<?php
namespace Faker\Tests\Faker\Formatter;

use Faker\Components\Faker\Formatter\GenerateEvent,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Tests\Base\AbstractProject;

class GenerateEventTest extends AbstractProject
{
    
    public function testEventInterface()
    {
        $composite = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                          ->getMock();
                          
        $type = new GenerateEvent($composite,array(),'table1');                  
        
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\Event',$type);
    }

    
    public function testProperties()
    {
        $composite = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                          ->getMock();
                          
        $values = array('value'=> 1);                  
        $id =  'table1';                 
        $type = new GenerateEvent($composite,$values,$id);  
        
        $this->assertSame($values,$type->getValues());
        $this->assertSame($id,$type->getId());
        $this->assertSame($composite,$type->getType());
        
    }
    
}

/* End of File */
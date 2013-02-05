<?php
namespace Faker\Tests\Engine\Original\Formatter;

use Faker\Components\Engine\Original\Formatter\GenerateEvent,
    Faker\Components\Engine\Original\Composite\CompositeInterface,
    Faker\Tests\Base\AbstractProject;

class GenerateEventTest extends AbstractProject
{
    
    public function testEventInterface()
    {
        $composite = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
                          ->getMock();
                          
        $type = new GenerateEvent($composite,array(),'table1');                  
        
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\Event',$type);
    }

    
    public function testProperties()
    {
        $composite = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')
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
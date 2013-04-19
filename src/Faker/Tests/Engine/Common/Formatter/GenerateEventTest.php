<?php
namespace Faker\Tests\Engine\Common\Formatter;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
    

class GenerateEventTest extends AbstractProject
{
    
    public function testEventInterface()
    {
        $composite = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')
                          ->getMock();
                          
        $type = new GenerateEvent($composite,array(),'table1');                  
        
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\Event',$type);
    }

    
    public function testProperties()
    {
        $composite = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')
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
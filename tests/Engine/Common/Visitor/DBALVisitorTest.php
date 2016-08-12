<?php
namespace Faker\Tests\Engine\Common\Visitor;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Visitor\DBALGathererVisitor;


class DBALVisitorTest extends AbstractProject
{

    public function testImplementsBaiscVisitor()
    {
        $valueMapper = $this->getMock('Faker\Components\Engine\Common\Formatter\ValueConverter');
        $visitor     = new DBALGathererVisitor($valueMapper);
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Visitor\BasicVisitor',$visitor);
        
    }
    
    
    
    public function testProperties()
    {
        $valueMapper = $this->getMock('Faker\Components\Engine\Common\Formatter\ValueConverter');
        $visitor     = new DBALGathererVisitor($valueMapper);
        
        $this->assertEquals($valueMapper,$visitor->getResult());
    }

    
    public function testVisitorAccept()
    {
        $composite    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\ColumnNode')->disableOriginalConstructor()->getMock();
        $valueMapper  = $this->getMock('Faker\Components\Engine\Common\Formatter\ValueConverter');
        $id           = 'column1';
        $doctrineType = $this->getMockBuilder('Doctrine\DBAL\Types\Type')
                         ->setMethods(array('getSQLDeclaration','getName','convertToDatabaseValue'))
                         ->disableOriginalConstructor()
                         ->getMock();
        
        $composite->expects($this->once())
                 ->method('getId')
                 ->will($this->returnValue($id));
                 
        $composite->expects($this->once())
                 ->method('getDBALType')
                 ->will($this->returnValue($doctrineType));
        
        $valueMapper->expects($this->once())
                    ->method('set')
                    ->with($this->equalTo($id),$this->equalTo($doctrineType));
        
        $visitor     = new DBALGathererVisitor($valueMapper);
        
        $visitor->visitDBALGatherer($composite);
        
    }
    
    
    public function testVisitorIgnoresNode()
    {
        $composite    = $this->getMock('Faker\Components\Engine\Common\Composite\CompositeInterface');
        $valueMapper  = $this->getMock('Faker\Components\Engine\Common\Formatter\ValueConverter');
        
        $visitor     = new DBALGathererVisitor($valueMapper);          
        $visitor->visitDBALGatherer($composite);
        
        #no call made to composite as not implementd DBAL type interface
        $this->assertTrue(true);        
    }
    
    
    public function testVisitorReset()
    {
        $valueMapper  = $this->getMock('Faker\Components\Engine\Common\Formatter\ValueConverter');
        $visitor     = new DBALGathererVisitor($valueMapper);          
        
        $visitor->reset();
    }
    
    
}
/* End of File */
<?php
namespace Faker\Tests\Engine\Common\Formatter;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Formatter\BaseFormatter;

class MockFormatter extends BaseFormatter
{
    
    public function getName()
    {
        return 'mockFormatter';
    }
    
    public function toXml()
    {
        return '';
    }
    
    public function getConfigExtension(ArrayNodeDefinition $rootNode)
    {
        return $rootNode;
    }
    
    public function getOuputFileFormat()
    {
        return '{prefix}_{body}_{suffix}_{seq}.{ext}';
    }
    
    public function getDefaultOutEncoding()
    {
        return 'UTF-8';
    }
    
};    
    
    
class BaseFormatterTest extends AbstractProject
{
    public function testOptionProperties()
    {
        $base = new MockFormatter();
        
        $mock = new \stdClass();
        $base->setOption('option1',1);
        $base->setOption('option2','anoption');
        $base->setOption('option3',$mock);
        $this->assertEquals(1,$base->getOption('option1'));
        $this->assertEquals('anoption',$base->getOption('option2'));
        $this->assertEquals($mock,$base->getOption('option3'));
        
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Option at option100 does not exist
      */
    public function testMissingOptionException()
    {
        $base = new MockFormatter();
        $base->getOption('option100');
        
    }
    
    
    public function testProperties()
    {
        $writer = $this->getMock('\Faker\Components\Writer\WriterInterface');
        $visitor = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\DBALGathererVisitor')->disableOriginalConstructor()->getMock();
        $event = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        
        $base = new MockFormatter();
        $base->setWriter($writer);
        $base->setVisitor($visitor);
        $base->setEventDispatcher($event);

        $this->assertEquals($writer,$base->getWriter());
        $this->assertEquals($visitor,$base->getVisitor());
        $this->assertEquals($event,$base->getEventDispatcher());
        
    }
    
    
    public function testMergeMissingDefaults()
    {
        $base = new MockFormatter();
        $base->merge();
        $this->assertEquals(false,$base->getOption('splitOnTable'));
        $this->assertEquals('{prefix}_{body}_{suffix}_{seq}.{ext}',$base->getOption('outFileFormat'));
            
    }
    
    public function testMergeMissingValues()
    {
        $base = new MockFormatter();
        $base->setOption('splitOnTable',null);
        $base->merge();
        $this->assertFalse($base->getOption('splitOnTable'));
    }
    
    /**
      *  @expectedException \Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage  Invalid type for path "config.splitOnTable". Expected boolean, but got string
      */
    public function testMergeBadValues()
    {
        $base = new MockFormatter();
        $base->setOption('splitOnTable','true');
        $base->merge();
        
    }
    
    
    public function testMergeGoodValues()
    {
        $base = new MockFormatter();
        $base->setOption('splitOnTable',true);
        $base->setOption('outFileFormat','{suffix}_{seq}.{ext}');
        $base->merge();
        $this->assertTrue($base->getOption('splitOnTable'));
        $this->assertEquals('{suffix}_{seq}.{ext}',$base->getOption('outFileFormat'));
    }
    
}
/* End of File */
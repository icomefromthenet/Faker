<?php
namespace Faker\Tests\Engine\Common\Composite;

use Faker\Components\Engine\Common\Composite\CompositeException;
use Faker\Tests\Base\AbstractProject;
use Faker\Tests\Engine\Common\Composite\Mock\MockRootNode;
use Faker\Tests\Engine\Common\Composite\Mock\MockNode;


/**
  *  Test the custom Exception can build path
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class CompositeExceptionTest extends AbstractProject
{
    
    public function testExceptionConstructor()
    {
        $node    = $this->getMockBuilder('Faker\Components\Engine\Common\Composite\CompositeInterface')->getMock();
        $msg     = 'no msg';
        $code    = 500;
        $previous = new \Exception();
        $exception = new CompositeException($node,$msg,$code,$previous);
    
        
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\EngineException',$exception);
        $this->assertEquals($node,$exception->getNode());
        $this->assertEquals($msg,$exception->getMessage());
        $this->assertEquals($code,$exception->getCode());
        $this->assertEquals($previous,$exception->getPrevious());
        
        
    }
    
    
    public function testBuildPathSimple()
    {
        $event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        
        $root    = new MockRootNode('root',$event);
        $childA  = new MockNode('childA',$event);
        $childB  = new MockNode('childB',$event);
        
        $root->addChild($childA);
        $root->addChild($childB);
        
        $this->assertEquals(array('childA','root'),CompositeException::buildPath($childA));
    }
    
    public function testBuildPathNested()
    {
        $event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        
        $root    = new MockRootNode('root',$event);
        $childA  = new MockNode('childA',$event);
        $childB  = new MockNode('childB',$event);
        
        $root->addChild($childA);
        $childA->addChild($childB);
        
        $this->assertEquals(array('childB','childA','root'),CompositeException::buildPath($childB));
    }
    
    
    public function testBuildPathRootNoChildren()
    {
        $event = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        
        $root    = new MockRootNode('root',$event);
        
        $this->assertEquals(array('root'),CompositeException::buildPath($root));
    }
    
    
}
/* End of File */
<?php
namespace Faker\Tests\Engine\Common\Datasource;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Composite\DatasourceNode;
use Faker\Tests\Engine\Common\Datasource\Mock\MockVisitor;

class DatasourceNodeTest extends AbstractProject
{
    
    public function testNodeDatasourcesInterface()
    {
        
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        
        $datasourceInternal = $this->getMock('Faker\Components\Engine\Common\Datasource\DatasourceInterface');
        $nodeId     = 'mynode';
        
        $node = new DatasourceNode($nodeId,$event,$datasourceInternal);   
        
        $aSources = $node->getDatasources();
        
        $this->assertInternalType('array',$aSources);
        $this->assertEquals($datasourceInternal,$aSources[0]);
        
       
    }
    
    public function testParentProperties()
    {
        
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $datasourceInternal = $this->getMock('Faker\Components\Engine\Common\Datasource\DatasourceInterface');
        $parentNode         = $this->getMock('Faker\Components\Engine\Common\Composite\CompositeInterface');   
        $nodeId     = 'mynode';
        
        $node = new DatasourceNode($nodeId,$event,$datasourceInternal);   
        
        $this->assertEquals($event,$node->getEventDispatcher());
        
        $node->setParent($parentNode);
        
        $this->assertEquals($parentNode,$node->getParent());
       
        $this->assertEquals($nodeId,$node->getId());
    }
    
    public function testVisitorInterface()
    {
        $visitor = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\BasicVisitor')
                        ->getMock();
        
        # add new visitors here when created 
        
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $datasourceInternal = $this->getMock('Faker\Components\Engine\Common\Datasource\DatasourceInterface');
        $nodeId     = 'mynode';
        
        $node = new DatasourceNode($nodeId,$event,$datasourceInternal);  
        
        $node->acceptVisitor($visitor);
        
    }
    
   /**
    * @expectedException Faker\Components\Engine\EngineException
    * @expectedExceptionMessage This node does not allow children
    */ 
   public function testNodeNotAcceptChildren()
   {
        $event  = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $datasourceInternal = $this->getMock('Faker\Components\Engine\Common\Datasource\DatasourceInterface');
        $childNode         = $this->getMock('Faker\Components\Engine\Common\Composite\CompositeInterface');   
        $nodeId     = 'mynode';
        
        $node = new DatasourceNode($nodeId,$event,$datasourceInternal);  
       
        $node->addChild($childNode);
   }
    
}
/* End of File */
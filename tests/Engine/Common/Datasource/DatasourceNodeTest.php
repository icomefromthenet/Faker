<?php
namespace Faker\Tests\Engine\Common\Datasource;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Composite\DatasourceNode;
use Faker\Tests\Engine\Common\Datasource\Mock\MockVisitor;

class DatasourceNodeTest extends AbstractProject
{
    
    public function testNodeDatasourcesInterface()
    {
        
        $event  = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        
        $datasourceInternalA = $this->createMock('Faker\Components\Engine\Common\Datasource\DatasourceInterface');
        $datasourceInternalB = $this->createMock('Faker\Components\Engine\Common\Datasource\DatasourceInterface');
        $nodeId     = 'mynode';
        
        $node = new DatasourceNode($nodeId,$event,$datasourceInternalA);   
        $this->assertEquals($datasourceInternalA,$node->getDatasource());
        
        $node->setDatasource($datasourceInternalB);
        $this->assertEquals($datasourceInternalB,$node->getDatasource());
       
    }
    
    public function testParentProperties()
    {
        
        $event  = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $datasourceInternal = $this->createMock('Faker\Components\Engine\Common\Datasource\DatasourceInterface');
        $parentNode         = $this->createMock('Faker\Components\Engine\Common\Composite\CompositeInterface');   
        $nodeId     = 'mynode';
        
        $node = new DatasourceNode($nodeId,$event,$datasourceInternal);   
        
        $this->assertEquals($event,$node->getEventDispatcher());
        
        $node->setParent($parentNode);
        
        $this->assertEquals($parentNode,$node->getParent());
       
        $this->assertEquals($nodeId,$node->getId());
    }
    
    public function testVisitorInterface()
    {
        $visitor = $this->createMock('Faker\Components\Engine\Common\Visitor\BasicVisitor');
        
        # add new visitors here when created 
        
        $event  = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $datasourceInternal = $this->createMock('Faker\Components\Engine\Common\Datasource\DatasourceInterface');
        $nodeId     = 'mynode';
        
        $node = new DatasourceNode($nodeId,$event,$datasourceInternal);  
        
        $node->acceptVisitor($visitor);
        
        $this->assertTrue(true);
        
    }
    
   /**
    * @expectedException Faker\Components\Engine\EngineException
    * @expectedExceptionMessage This node does not allow children
    */ 
   public function testNodeNotAcceptChildren()
   {
        $event  = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $datasourceInternal = $this->createMock('Faker\Components\Engine\Common\Datasource\DatasourceInterface');
        $childNode         = $this->createMock('Faker\Components\Engine\Common\Composite\CompositeInterface');   
        $nodeId     = 'mynode';
        
        $node = new DatasourceNode($nodeId,$event,$datasourceInternal);  
       
        $node->addChild($childNode);
   }
    
}
/* End of File */
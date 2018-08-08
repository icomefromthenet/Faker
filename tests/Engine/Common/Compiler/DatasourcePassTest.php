<?php
namespace Faker\Tests\Engine\Common\Compiler;

use Faker\Components\Engine\Common\Compiler\Pass\DatasourcePass;
use Faker\Components\Engine\Common\Compiler\Graph\DirectedGraph;
use Faker\Components\Engine\Common\Visitor\DSourceInjectorVisitor;
use Faker\Components\Engine\Common\Composite\PathBuilder;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\DB\Composite\SchemaNode;
use Faker\Components\Engine\DB\Composite\TableNode;
use Faker\Components\Engine\DB\Composite\ColumnNode;
use Faker\Components\Engine\DB\Composite\ForeignKeyNode;
use Faker\PlatformFactory;
use Faker\Tests\Base\AbstractProject;
use Faker\Components\Config\ConnectionPool;
use Faker\Components\Config\Entity;


class DatasourcePassTest extends AbstractProject
{

    
    public function testPassExecutesVisitor()
    {
        # assert schema is passed visitor
        $schema = $this->getMockBuilder('Faker\\Components\\Engine\\DB\\Composite\\SchemaNode')
             ->disableOriginalConstructor()
             ->setMethods(array('acceptVisitor'))
             ->getMock();
             
        $compiler = $this->createMock('Faker\\Components\\Engine\\Common\\Compiler\\CompilerInterface');
        
        $path      = new PathBuilder();
        $platformFactory = new PlatformFactory();
        $pool    = new ConnectionPool($platformFactory);
        
        $visitor = new DSourceInjectorVisitor($path);
        $pass    = new DatasourcePass($visitor,$pool);
        
        # assert visitor property 
        $this->assertEquals($visitor,$pass->getSourceVisitor());
        
        # assert the visitor is used
        $schema->expects($this->once())  
               ->method('acceptVisitor')
               ->with($this->equalTo($visitor));
             
        $pass->process($schema,$compiler);  
    }
    
    public function testInsertsCorrectConnection()
    {
        
        $uniqueConnectionNameA = 'connection.a';
        $uniqueConnectionNameB = 'connection.b'; 
        
        # assert schema is passed visitor
        $schema = $this->getMockBuilder('Faker\\Components\\Engine\\DB\\Composite\\SchemaNode')
             ->disableOriginalConstructor()
             ->setMethods(array('acceptVisitor'))
             ->getMock();
        
        
        $mockDatasourceA       = $this->getMockBuilder('Faker\\Tests\\Engine\\Common\\Datasource\\Mock\\MockDatasourceExtra')
                                      ->disableOriginalConstructor()
                                      ->setMethods(array('initSource'))
                                      ->getMock();
                                      
        $mockDatasourceB       = $this->getMockBuilder('Faker\\Tests\\Engine\\Common\\Datasource\\Mock\\MockDatasourceExtra')
                                      ->disableOriginalConstructor()
                                      ->setMethods(array('initSource'))
                                      ->getMock();
        
        // check that init is called 
        $mockDatasourceA->expects($this->once())
                        ->method('initSource');
                        
        $mockDatasourceB->expects($this->once())
                        ->method('initSource');
                        
        
        # set the connection name options
        $mockDatasourceA->setOption('connectionName',$uniqueConnectionNameA);
        $mockDatasourceB->setOption('connectionName',$uniqueConnectionNameB);
        
        
        # create the source list that the visitor should return
        $mockSourceCompoisteNodeA     = $this->getMockBuilder('Faker\\Tests\\Engine\\Common\\Datasource\\Mock\\MockDatasourceNode')
                                ->disableOriginalConstructor()
                                ->setMethods(array('getDatasource'))
                                ->getMock();
        
        $mockSourceCompositeNodeB     = $this->getMockBuilder('Faker\\Tests\\Engine\\Common\\Datasource\\Mock\\MockDatasourceNode')
                                ->disableOriginalConstructor()
                                ->setMethods(array('getDatasource'))
                                ->getMock();
        
        $mockSourceCompoisteNodeA->expects($this->once())
                                ->method('getDatasource')
                                ->will($this->returnValue($mockDatasourceA));
       
        
        $mockSourceCompositeNodeB->expects($this->once())
                                ->method('getDatasource')
                                ->will($this->returnValue($mockDatasourceB));
        
                         
                               
        $mockSourceList = array(
            'uniqueNameA' => $mockSourceCompoisteNodeA
            ,'uniqueNameB' => $mockSourceCompositeNodeB
        );
        
       
        
        $compiler        = $this->createMock('Faker\\Components\\Engine\\Common\\Compiler\\CompilerInterface');
        
        
        
        
        $visitorMock     = $this->getMockBuilder('Faker\\Components\\Engine\\Common\\Visitor\\DSourceInjectorVisitor')
                                ->disableOriginalConstructor()
                                ->getMock();
        
        $visitorMock->expects($this->once())
                    ->method('getResult')
                    ->will($this->returnValue($mockSourceList));
        
        # create the plaform factory and add new connections
                    
        $platformFactory = new PlatformFactory();
        $pool            = new ConnectionPool($platformFactory);
        
        $connectionA   = new Entity();
        $connectionB   = new Entity();
        
        $connectionA->setType('pdo_sqlite');
        $connectionA->setMemory(true);
        $pool->addExtraConnection($uniqueConnectionNameA,$connectionA);
        
        $connectionB->setType('pdo_sqlite');
        $connectionB->setMemory(true);
        $pool->addExtraConnection($uniqueConnectionNameB,$connectionB);
    
        # create the pass and execute the function
                        
        $pass    = new DatasourcePass($visitorMock,$pool);
        
        $pass->process($schema,$compiler); 
        
        # assert that each datasource has correct connection
        
        $connectA = $pool->getExtraConnection($uniqueConnectionNameA);
        $connectB = $pool->getExtraConnection($uniqueConnectionNameB);
        
        $this->assertEquals($mockDatasourceA->getExtraConnection(),$connectA);
        $this->assertEquals($mockDatasourceB->getExtraConnection(),$connectB);
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage Connection pool does not have connection badConnectionName
     */ 
    public function testExceptionWhenConnectionDoesNotExist()
    {
        $uniqueConnectionNameA = 'connection.a';
       
        
        # assert schema is passed visitor
        $schema = $this->getMockBuilder('Faker\\Components\\Engine\\DB\\Composite\\SchemaNode')
             ->disableOriginalConstructor()
             ->setMethods(array('acceptVisitor'))
             ->getMock();
             
        
        $mockDatasourceA       = $this->getMockBuilder('Faker\\Tests\\Engine\\Common\\Datasource\\Mock\\MockDatasourceExtra')
                                      ->disableOriginalConstructor()
                                      ->setMethods(null)
                                      ->getMock();
                                      
        
        # set the connection name options
        $mockDatasourceA->setOption('connectionName','badConnectionName');
       
        
        
        # create the source list that the visitor should return
        $mockSourceCompoisteNodeA     = $this->getMockBuilder('Faker\\Tests\\Engine\\Common\\Datasource\\Mock\\MockDatasourceNode')
                                ->disableOriginalConstructor()
                                ->setMethods(array('getDatasource'))
                                ->getMock();
        
        
        $mockSourceCompoisteNodeA->expects($this->once())
                                ->method('getDatasource')
                                ->will($this->returnValue($mockDatasourceA));
       
        
                         
                               
        $mockSourceList = array(
            'uniqueNameA' => $mockSourceCompoisteNodeA
        );
        
        $compiler        = $this->createMock('Faker\\Components\\Engine\\Common\\Compiler\\CompilerInterface');
        
        
        $visitorMock     = $this->getMockBuilder('Faker\\Components\\Engine\\Common\\Visitor\\DSourceInjectorVisitor')
                                ->disableOriginalConstructor()
                                ->getMock();
        
        $visitorMock->expects($this->once())
                    ->method('getResult')
                    ->will($this->returnValue($mockSourceList));
        
        # create the plaform factory and add new connections
                    
        $platformFactory = new PlatformFactory();
        $pool            = new ConnectionPool($platformFactory);
        
        $connectionA   = new Entity();
      
        $connectionA->setType('pdo_sqlite');
        $connectionA->setMemory(true);
        $pool->addExtraConnection($uniqueConnectionNameA,$connectionA);
        
      
        # create the pass and execute the function
                        
        $pass    = new DatasourcePass($visitorMock,$pool);
        
        $pass->process($schema,$compiler);  
    }
    
    public function testPassExecuteWithNoErrorEmptySourceList()
    {
        $schema = $this->getMockBuilder('Faker\\Components\\Engine\\DB\\Composite\\SchemaNode')
             ->disableOriginalConstructor()
             ->setMethods(array('acceptVisitor'))
             ->getMock();
             
        
        $compiler        = $this->createMock('Faker\\Components\\Engine\\Common\\Compiler\\CompilerInterface');
        
        
        $visitorMock     = $this->getMockBuilder('Faker\\Components\\Engine\\Common\\Visitor\\DSourceInjectorVisitor')
                                ->disableOriginalConstructor()
                                ->getMock();
        
        $visitorMock->expects($this->once())
                    ->method('getResult')
                    ->will($this->returnValue(array()));
        
        $platformFactory = new PlatformFactory();
        $pool            = new ConnectionPool($platformFactory);
        
        $pass    = new DatasourcePass($visitorMock,$pool);
        
        $pass->process($schema,$compiler); 
        
    }
   
   public function testPassWithNoErrorOnListWithNoExtraConnectInterface()
   {
       
        $uniqueConnectionNameA = 'connection.a';
       
        
        # assert schema is passed visitor
        $schema = $this->getMockBuilder('Faker\\Components\\Engine\\DB\\Composite\\SchemaNode')
             ->disableOriginalConstructor()
             ->setMethods(array('acceptVisitor'))
             ->getMock();
             
        
        $mockDatasourceA       = $this->getMockBuilder('Faker\\Tests\\Engine\\Common\\Datasource\\Mock\\MockDatasource')
                                      ->disableOriginalConstructor()
                                      ->setMethods(null)
                                      ->getMock();
                                      
        
        # create the source list that the visitor should return
        $mockSourceCompoisteNodeA     = $this->getMockBuilder('Faker\\Tests\\Engine\\Common\\Datasource\\Mock\\MockDatasourceNode')
                                ->disableOriginalConstructor()
                                ->setMethods(array('getDatasource'))
                                ->getMock();
        
        
        $mockSourceCompoisteNodeA->expects($this->once())
                                ->method('getDatasource')
                                ->will($this->returnValue($mockDatasourceA));
       
                         
                               
        $mockSourceList = array(
            'uniqueNameA' => $mockSourceCompoisteNodeA
        );
        
        $compiler        = $this->createMock('Faker\\Components\\Engine\\Common\\Compiler\\CompilerInterface');
        
        
        $visitorMock     = $this->getMockBuilder('Faker\\Components\\Engine\\Common\\Visitor\\DSourceInjectorVisitor')
                                ->disableOriginalConstructor()
                                ->getMock();
        
        $visitorMock->expects($this->once())
                    ->method('getResult')
                    ->will($this->returnValue($mockSourceList));
        
        # create the plaform factory and add new connections
                    
        $platformFactory = new PlatformFactory();
        $pool            = new ConnectionPool($platformFactory);
        
        $connectionA   = new Entity();
      
        $connectionA->setType('pdo_sqlite');
        $connectionA->setMemory(true);
        $pool->addExtraConnection($uniqueConnectionNameA,$connectionA);
        
      
        # create the pass and execute the function
                        
        $pass    = new DatasourcePass($visitorMock,$pool);
        
        $pass->process($schema,$compiler); 
       
       
   }
   
}
/* End of File */
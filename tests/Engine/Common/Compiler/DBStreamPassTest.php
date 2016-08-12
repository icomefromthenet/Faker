<?php
namespace Faker\Tests\Engine\Common\Compiler;

use Faker\Components\Engine\Common\Compiler\Pass\DbStreamRepPass;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\DB\Composite\SchemaNode;
use Faker\Components\Engine\DB\Composite\TableNode;
use Faker\Components\Engine\DB\Composite\ColumnNode;
use Faker\Components\Engine\Common\Composite\FormatterNode;
use Faker\Components\Engine\DB\Composite\ForeignKeyNode;
use Faker\Tests\Base\AbstractProject;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Faker\Components\Engine\Common\Formatter\Sql;
use Faker\Components\Engine\Common\Visitor\DBALGathererVisitor;
use Faker\PlatformFactory;
use Faker\Components\Config\ConnectionPool;
use Faker\Components\Config\Entity;


class DBStreamPassTest extends AbstractProject
{
    
    public function testPassProperties()
    {
        $sqlPlatform = new MySqlPlatform(); 
        
        $event      = $this->getMockBuilder('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $mockWritter = $this->getMockBuilder('Faker\\Components\\Writer\\Writer')->disableOriginalConstructor()->getMock();
        
        $mockWritter->expects($this->once())
                    ->method('setStream')
                    ->with($this->isInstanceOf('Faker\\Components\\Writer\\DatabaseStream'));
        
        # create the formatter
        
        $formatter = new Sql($event,$mockWritter,$sqlPlatform, new DBALGathererVisitor(),array());
        
        $formatter->setOption(sql::CONFIG_WRITE_TO_DATABASE,'testConnect');
        
        # create the composite
        
        $schemaNode      = new SchemaNode('schema',$event);
        $formatterNode   = new FormatterNode('formatterNode',$event,$formatter);
        
        $schemaNode->addChild($formatterNode);
        
        
        # add in memory sql connection
        $platformFactory = new PlatformFactory();
        $connectPool     = new ConnectionPool($platformFactory);
        
        $entity = new Entity();
        
        $entity->setType('pdo_sqlite');
        $entity->setMemory(true);
        $connectPool->addExtraConnection('testConnect',$entity);
        
        $this->assertTrue($connectPool->hasExtraConnection('testConnect'));
        
        # create the new pass and assert properties
        $pass = new DbStreamRepPass($connectPool,$this->getProject()->getWriterManager());
        
        $this->assertSame($connectPool,$pass->getConnectionPool());
        $this->assertSame($this->getProject()->getWriterManager(),$pass->getWriterManager());
        
        # test with valid schema
        
        $mockCompiler = $this->getMock('Faker\\Components\\Engine\\Common\\Compiler\\CompilerInterface');
    
        # execute pass (mock writer will assert correct stream is set)    
        $pass->process($schemaNode,$mockCompiler);
    }
    
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage The database connection at name::testConnect cant not be matched to a connection in config
     */ 
    public function testFailesonBadConnection()
    {
        $sqlPlatform = new MySqlPlatform(); 
        
        $event      = $this->getMockBuilder('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $mockWritter = $this->getMockBuilder('Faker\\Components\\Writer\\Writer')->disableOriginalConstructor()->getMock();
        
        # create the formatter
        
        $formatter = new Sql($event,$mockWritter,$sqlPlatform, new DBALGathererVisitor(),array());
        
        $formatter->setOption(sql::CONFIG_WRITE_TO_DATABASE,'testConnect');
        
        # create the composite
        
        $schemaNode      = new SchemaNode('schema',$event);
        $formatterNode   = new FormatterNode('formatterNode',$event,$formatter);
        
        $schemaNode->addChild($formatterNode);
        
        
        # add in memory sql connection
        $platformFactory = new PlatformFactory();
        $connectPool     = new ConnectionPool($platformFactory);
        
        
        # create the new pass and assert properties
        $pass = new DbStreamRepPass($connectPool,$this->getProject()->getWriterManager());
        
        
        # test with valid schema
        $mockCompiler = $this->getMock('Faker\\Components\\Engine\\Common\\Compiler\\CompilerInterface');
        
        $pass->process($schemaNode,$mockCompiler);
        
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage The testConnect is set to read only unable to create Database Stream Writer
     * 
     */ 
    public function testFailReadOnly()
    {
        $sqlPlatform = new MySqlPlatform(); 
        
        $event      = $this->getMockBuilder('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface')->getMock();
        $mockWritter = $this->getMockBuilder('Faker\\Components\\Writer\\Writer')->disableOriginalConstructor()->getMock();
        
        # create the formatter
        
        $formatter = new Sql($event,$mockWritter,$sqlPlatform, new DBALGathererVisitor(),array());
        
        $formatter->setOption(sql::CONFIG_WRITE_TO_DATABASE,'testConnect');
        
        # create the composite
        
        $schemaNode      = new SchemaNode('schema',$event);
        $formatterNode   = new FormatterNode('formatterNode',$event,$formatter);
        
        $schemaNode->addChild($formatterNode);
        
        
        # add in memory sql connection
        $platformFactory = new PlatformFactory();
        $connectPool     = new ConnectionPool($platformFactory);
        
        $entity = new Entity();
        
        $entity->setType('pdo_sqlite');
        $entity->setMemory(true);
        $entity->setReadOnlyMode(true);
        $connectPool->addExtraConnection('testConnect',$entity);
        
        
        $this->assertTrue($connectPool->hasExtraConnection('testConnect'));
        
        # create the new pass and assert properties
        $pass = new DbStreamRepPass($connectPool,$this->getProject()->getWriterManager());
        
        $this->assertSame($connectPool,$pass->getConnectionPool());
        $this->assertSame($this->getProject()->getWriterManager(),$pass->getWriterManager());
        
        # test with valid schema
        
        $mockCompiler = $this->getMock('Faker\\Components\\Engine\\Common\\Compiler\\CompilerInterface');
    
        # execute pass (mock writer will assert correct stream is set)    
        $pass->process($schemaNode,$mockCompiler);
    }
    

}
/* End of File */
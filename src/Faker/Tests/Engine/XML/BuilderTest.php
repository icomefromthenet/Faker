<?php
namespace Faker\Tests\Engine\XML\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\XML\Builder\NodeBuilder;
use Faker\Components\Engine\XML\Composite\SchemaNode;
use Faker\Components\Engine\Common\Formatter\Phpunit;
use Faker\Components\Engine\Common\Formatter\Sql;
use Faker\Components\Engine\Common\BuildEvents;
use Faker\Components\Engine\Common\BuildEvent;

class BuilderTest extends AbstractProject
{
    
    
    public function testBuilderDIBuilder()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $this->assertInstanceOf('Faker\\Components\\Engine\\XML\\Builder\\NodeBuilder',$builder);
        
    }
    
    
    public function testBuilderAddSchema()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $builder->addSchema('myschema');
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\XML\\Composite\\SchemaNode',$builder->getSchema());
        $this->assertInstanceOf('Faker\\Components\\Engine\\XML\\Composite\\SchemaNode',$builder->getHead());
        $this->assertEquals('myschema',$builder->getSchema()->getId());
        $this->assertEquals('en',$builder->getSchema()->getOption('locale','en'));
        
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage Scheam already added only have one
    */
    public function testBuilderOneSchemaOnly()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $builder->addSchema('myschema');
        $builder->addSchema('newschema');
    }
    
    
    public function testBuilderSchemaAcceptsOptions()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $locale = 'uk';
        $builder->addSchema('myschema',array('locale'=>$locale));
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\XML\\Composite\\SchemaNode',$builder->getSchema());
        $this->assertEquals($locale,$builder->getSchema()->getOption('locale'));
        
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage Must have set a schema before adding writter
    */
    public function testBuilderAddWritterFailsNoSchema()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $writer  = $builder->addWriter('mysql','phpunit');
        
    }
    
    public function testBuilderAddWritter()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $builder->addSchema('myschema',array());
        $writer  = $builder->addWriter('mysql','phpunit'); 
        
        $schemaChildren = $builder->getHead()->getChildren();
        $this->assertInstanceOf('Faker\\Components\\Engine\\XML\\Composite\\FormatterNode',$schemaChildren[0]);
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Formatter\\Phpunit',$schemaChildren[0]->getInternal());
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage Formatter does not exist at::baaaa
    */
    public function testBuilderAddWritterNotExist()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $builder->addSchema('myschema',array());
        $writer  = $builder->addWriter('mysql','baaaa'); 
 
        
    }
    
    
    public function testBuilderAddTable()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $builder->addSchema('myschema',array());
        
        $builder->addTable('mytable',array());
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\XML\\Composite\\TableNode',$builder->getHead());
        
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage Must add a scheam first before adding a table
    */   
    public function testBuilderAddTableOnlySchemaHead()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        
        $builder->addTable('mytable',array());
    }
    
    
    public function testBuilderTableOptionSet()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $builder->addSchema('myschema',array('locale' => 'en'));
        
        $builder->addTable('mytable',array('locale' => 'us'));
        
        $this->assertEquals('us',$builder->getHead()->getOption('locale'));
    }
    
    public function testBuilderInhertsLocale()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $builder->addSchema('myschema',array('locale' => 'us'));
        
        $builder->addTable('mytable',array());
        
        $this->assertEquals('us',$builder->getHead()->getOption('locale'));
    }
    
    public function testBuilderAddColumn()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       
       $builder->addColumn('columnA',array('type'=> 'string'));
       $this->assertInstanceOf('Faker\\Components\\Engine\\XML\\Composite\\ColumnNode',$builder->getHead());
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage Column requires a doctrine type
    */ 
    public function testBuilderAddColumnErrorNoDBALType()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       
       $builder->addColumn('columnA'); 
        
    }
    
    
    public function testBuilderAddColumnSetsOptions()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       
       $builder->addColumn('columnA',array('type'=> 'string','generator'=> 'srandom'));
       $this->assertEquals('srandom',$builder->getHead()->getOption('generator'));
    }
    
    
    public function testBuilderAddForeignKey()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       $builder->addColumn('columnA',array('type'=> 'string'));
       
       $builder->addForeignKey('myfk1',array('foreignColumn' => null,'foreignTable' => null));
       $this->assertInstanceOf('Faker\\Components\\Engine\\XML\\Composite\\ForeignKeyNode',$builder->getHead());
        
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage Can not add a Foreign-Key without first setting a column
    */ 
    public function testBuilderErrorAddForeignKeyNoColumnHead()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       
       $builder->addForeignKey('myfk1',array('foreignColumn' => null,'foreignTable' => null));
    }
    
    
    public function testBuilderAddType()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       $builder->addColumn('columnA',array('type'=> 'string'));
       
       $builder->addType('alphanumeric',array('option2' => null));
        
       $this->assertInstanceOf('Faker\\Components\\Engine\\XML\\Composite\\TypeNode',$builder->getHead());
       $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Type\\Type',$builder->getHead()->getType()); 
    }
    
    
    public function testBuilderAddTypeOptionsSet()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       $builder->addColumn('columnA',array('type'=> 'string'));
       
       $builder->addType('alphanumeric',array('option2' => true,'option3' => null));
       
       $this->assertTrue($builder->getHead()->getOption('option2'));
       $this->assertNull($builder->getHead()->getOption('option3')); 
    }
    
    public function testBuilderSetTypeOption()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       $builder->addColumn('columnA',array('type'=> 'string'));
       
       $builder->addType('alphanumeric',array('option2' => true,'option3' => null));  
        
       $builder->setTypeOption('ab','cdef');
       
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage Type has not been set, can not accept option
    */ 
    public function testBuilderErrorSetTypeOptionNotOptionInterface()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $builder->setTypeOption('ab','cdef');
    }
    
    public function testBuilderAddSelectorAlternateSelector()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       $builder->addColumn('columnA',array('type'=> 'string'));
       
       $builder->addSelector('alternate',array());
       
       $this->assertInstanceOf('Faker\\Components\\Engine\\XML\\Composite\\SelectorNode',$builder->getHead());
       $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Selector\\AlternateSelector',$builder->getHead()->getInternal());
       
    }
    
    
    public function testBuilderAddSelectorPick()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       $builder->addColumn('columnA',array('type'=> 'string'));
       
       $builder->addSelector('pick',array());
       
       $this->assertInstanceOf('Faker\\Components\\Engine\\XML\\Composite\\SelectorNode',$builder->getHead());
       $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Selector\\PickSelector',$builder->getHead()->getInternal());
       
    }
    
    
    public function testBuilderAddSelectorRandom()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       $builder->addColumn('columnA',array('type'=> 'string'));
       
        $builder->addSelector('random',array());
       
       $this->assertInstanceOf('Faker\\Components\\Engine\\XML\\Composite\\SelectorNode',$builder->getHead());
       $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Selector\\RandomSelector',$builder->getHead()->getInternal());
       
    }
    
    
    public function testBuilderAddSelectorSwap()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       $builder->addColumn('columnA',array('type'=> 'string'));
       
        $builder->addSelector('swap',array());
       
       $this->assertInstanceOf('Faker\\Components\\Engine\\XML\\Composite\\SelectorNode',$builder->getHead());
       $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Selector\\SwapSelector',$builder->getHead()->getInternal());
       
    }
    
    public function testBuilderAddSelectorWhenNode()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       $builder->addColumn('columnA',array('type'=> 'string'));
       
       $builder->addSelector('swap',array());
       $builder->addSelector('when',array('at'=>100));
       
       $this->assertInstanceOf('Faker\\Components\\Engine\\XML\\Composite\\WhenNode',$builder->getHead());
       
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage When node must have a selector as a parent
    */ 
    public function testBuilderAddSelectorWhenNodeNoSelectorParent()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       $builder->addColumn('columnA',array('type'=> 'string'));
       
       $builder->addSelector('when',array('at'=>100));
       
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage When node must have a swap node as parent
    */ 
    public function testBuilderAddSelectorWhenNodeNoSwapParent()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       $builder->addColumn('columnA',array('type'=> 'string'));
       $builder->addSelector('random',array());
       
       $builder->addSelector('when',array('at'=>100));
       
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage Unknown Selector:: zzzz
    */ 
    public function testBuilderAddSelectorErrorUnknownNode()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       $builder->addColumn('columnA',array('type'=> 'string'));
       $builder->addSelector('zzzz',array());
       
    }
    
    public function testValidateWithValidNodeSet()
    {
        # override the event handler with mock
        $project = $this->getProject();
        $event = $project['event_dispatcher'] = $this->getMock('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface');
        
        
        # listen for validation events
        $event->expects($this->at(0))
              ->method('dispatch')
              ->with($this->equalTo(BuildEvents::onValidationStart),$this->isInstanceOf('Faker\\Components\\Engine\\Common\\BuildEvent'));
        
        $event->expects($this->at(1))
              ->method('dispatch')
              ->with($this->equalTo(BuildEvents::onValidationEnd),$this->isInstanceOf('Faker\\Components\\Engine\\Common\\BuildEvent'));
        
        # fetch the builder with mock event handler
        $builder = $project->getXMLEngineBuilder();
        
        
        # build a schema
        $builder->addSchema('myschema',array());
        $builder->addTable('MyTable',array('generate' => 100));
        $builder->end();
        
        $builder->addTable('mySecondTable',array('generate'=> 200));
        $builder->end();
        
        
        # attempt to validate
        $builder->validate();
        
    }
    
    /**
     * @expectedException Faker\Components\Engine\Common\Composite\CompositeException
     * @expectedExceptionMessage The child node "generate" at path "config" must be configured.
    */
    public function testValidateWithInsValidNodeSet()
    {
         # override the event handler with mock
        $project = $this->getProject();
        $event = $project['event_dispatcher'] = $this->getMock('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface');
        
        
        # listen for validation events
        $event->expects($this->at(0))
              ->method('dispatch')
              ->with($this->equalTo(BuildEvents::onValidationStart),$this->isInstanceOf('Faker\\Components\\Engine\\Common\\BuildEvent'));
        
        # fetch the builder with mock event handler
        $builder = $project->getXMLEngineBuilder();
        
        
        # build a schema
        $builder->addSchema('myschema',array());
        $builder->addTable('MyTable',array());
        $builder->end();
        
        $builder->addTable('mySecondTable',array('generate'=> 200));
        $builder->end();
        
        
        # attempt to validate
        $builder->validate();
    }
    
    
     public function testCompileWithValidNodeSet()
    {
        # override the event handler with mock
        $project = $this->getProject();
        $event = $project['event_dispatcher'] = $this->getMock('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface');
        
        
        # listen for validation events
        $event->expects($this->at(2))
              ->method('dispatch')
              ->with($this->equalTo(BuildEvents::onCompileStart),$this->isInstanceOf('Faker\\Components\\Engine\\Common\\BuildEvent'));
        
        $event->expects($this->at(3))
              ->method('dispatch')
              ->with($this->equalTo(BuildEvents::onCompileEnd),$this->isInstanceOf('Faker\\Components\\Engine\\Common\\BuildEvent'));
        
        # fetch the builder with mock event handler
        $builder = $project->getXMLEngineBuilder();
        
        
        # build a schema
        $builder->addSchema('myschema',array());
        $builder->addTable('MyTable',array('generate' => 100));
        $builder->end();
        
        $builder->addTable('mySecondTable',array('generate'=> 200));
        $builder->end();
        
        
        # attempt to validate
        $builder->validate();
        $builder->compile();
        
    }
    
    
    
    public function testAddDatasourceNoDefinition()
    {
        $project = $this->getProject();
        $sourceName = 'MyMockSource';
        
        # add mock datasource to repo
        $project['engine_common_datasource_repo']->registerExtension('mockphpsource','\\Faker\\Tests\\Engine\\XML\\Mock\\MockPHPSource');
        
        $builder = $project->getXMLEngineBuilder();
        
        # build a schema
        $builder->addSchema('myschema',array());    
        
        # assert the fulent interface
        $this->assertEquals($builder,$builder->addDatasource('mockphpsource',array('name' => 'mockphpsource','id'=>$sourceName)));
        
        # assert we have a datasource Composite Node
        $datasourceNode = $builder->getHead();
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Composite\\DatasourceNode',$datasourceNode);
        
        # assert that have correct internal datasource
        $this->assertInstanceOf('\\Faker\\Tests\\Engine\\XML\\Mock\\MockPHPSource',$datasourceNode->getDatasource());
    
        
    }
    
    
    public function testAddDatasourceWithDefinition()
    {
        $project = $this->getProject();
        $sourceName = 'MyMockSource';
        
        # add mock datasource to repo
        $project['engine_common_datasource_repo']->registerExtension('mockphpdef','Faker\\Tests\\Engine\\XML\\Mock\\MockDefinition');
        
        $builder = $project->getXMLEngineBuilder();
        
        # build a schema
        $builder->addSchema('myschema',array());    
        
        # assert the fulent interface
        $this->assertEquals($builder,$builder->addDatasource('mockphpdef',array('name' => 'mockphpdef','id'=>$sourceName)));
        
        # assert we have a datasource Composite Node
        $datasourceNode = $builder->getHead();
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Composite\\DatasourceNode',$datasourceNode);
        
        # assert that have correct internal datasource
        $this->assertInstanceOf('\\Faker\\Tests\\Engine\\XML\\Mock\\MockPHPSource',$datasourceNode->getDatasource());
    
        
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage Datasource not exist at mockphpnotexist
     */ 
    public function testErrorAddDatasourceNotKnown()
    {
        $project = $this->getProject();
        $builder = $project->getXMLEngineBuilder();
        
        # build a schema
        $builder->addSchema('myschema',array());    
        
        $builder->addDatasource('mockphpnotexist'
                                ,array('name' => 'mockphpnotexist','id'=>'thesourceId'));
      
    }
   
    public function testBuilderAddFromsource()
    {
       $builder = $this->getProject()->getXMLEngineBuilder();
       $builder->addSchema('myschema',array());
       $builder->addTable('mytable',array()); 
       $builder->addColumn('columnA',array('type'=> 'string'));
       
       $builder->addType('fromsource',array('name'=>'phpsource','source'=>'mysource'));
       
       
       $this->assertInstanceOf('Faker\\Components\\Engine\\XML\\Composite\\TypeNode',$builder->getHead());
       $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Type\\FromSource',$builder->getHead()->getType());
       
    }
    
}
/* End of File */
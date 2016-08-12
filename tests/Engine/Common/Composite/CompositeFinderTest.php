<?php
namespace Faker\Tests\Engine\Common\Composite;

use Faker\Components\Engine\Common\Composite\CompositeFinder;
use Faker\Components\Engine\Common\Composite\PathBuilder;
use Faker\Tests\Base\AbstractProject;

use Faker\Components\Engine\Common\Composite\SchemaNode;
use Faker\Components\Engine\Common\Composite\TableNode;
use Faker\Components\Engine\Common\Composite\ColumnNode;
use Faker\Components\Engine\Common\Composite\ForeignKeyNode;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Tests\Engine\Common\Datasource\Mock\MockDatasource;
use Faker\Components\Engine\Common\Composite\DatasourceNode;

/**
  *  Test the node implementes CompositeInterface correctly
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class CompositeFinderTest extends AbstractProject
{
    
    protected function getMockComposite()
    {
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema    = new SchemaNode('schema',$event);
        
        $tableA    = new TableNode('tableA',$event);
        $columnA1  = new ColumnNode('columnA1',$event);
        $columnA2  = new ColumnNode('columnA2',$event);
        
        $tableB    = new TableNode('tableB',$event);
        $columnB1  = new ColumnNode('columnB1',$event);
        $columnB2  = new ColumnNode('columnB2',$event);
        
        $tableC    = new TableNode('tableC',$event);
        $columnC1  = new ColumnNode('columnC1',$event);
        $columnC2  = new ColumnNode('columnC2',$event);
        $fkc1      = new ForeignKeyNode('fkc1',$event);
        
        
        # link columns to tables
        $columnC2->addChild($fkc1);
        $tableC->addChild($columnC1);
        $tableC->addChild($columnC2);
        
        $tableB->addChild($columnB1);
        $tableB->addChild($columnB2);
        
        $tableA->addChild($columnA1);
        $tableA->addChild($columnA2);
        
        $schema->addChild($tableA);
        $schema->addChild($tableB);
        $schema->addChild($tableC);
        
        # create a type
        $mockType =  $this->getMockBuilder('Faker\\Components\\Engine\\Common\\Type\\Type')
                      ->disableOriginalConstructor()
                      ->getMock();
        
        $typeNode = new TypeNode('TypeNodeA',$event,$mockType);
        
        $columnA2->addChild($typeNode);
        
        # create a datasource
        $mockDatasourceA = new MockDatasource();
        $dataSourceNodeA = new DatasourceNode('DatasourceNodeA',$event,$mockDatasourceA);
        
        $mockDatasourceB = new MockDatasource();
        $dataSourceNodeB = new DatasourceNode('DatasourceNodeB',$event,$mockDatasourceB);
        
        $schema->addChild($dataSourceNodeA);
        $schema->addChild($dataSourceNodeB);
        
        return $schema;
    }
    
    
    
    public function testParentSchema()
    {
        $schema = $this->getMockComposite();
        $finder = new CompositeFinder();
        $child  = $schema->getChildren();
        
        $finder->set($schema);
        $this->assertEquals('schema',$finder->parentSchema()->get()->getId());
        
        $finder->set($child[0]);
        $this->assertEquals('schema',$finder->parentSchema()->get()->getId());
        
        $columns = $child[2]->getChildren();
        $finder->set($columns[0]);
        $this->assertEquals('schema',$finder->parentSchema()->get()->getId());
        
    }
    
    
    public function testFindTable()
    {
        $schema = $this->getMockComposite();
        $finder = new CompositeFinder();
        
        $finder->set($schema);
        $this->assertEquals('tableC',$finder->table('tableC')->get()->getId());
        $this->assertEquals('tableC',$finder->table('tableC')->get()->getId());
        
        $finder->set($schema);
        $this->assertEquals(null,$finder->table('tableD')->get());
        
    }
    
    
    public function testFindColumn()
    {
        $schema = $this->getMockComposite();
        $tables = $schema->getChildren();
        $finder = new CompositeFinder();
        
        $finder->set($tables[1]);
        $this->assertEquals('columnB1',$finder->column('columnB1')->get()->getId());
        $this->assertEquals('columnB1',$finder->column('columnB1')->get()->getId());
        
        $finder->set($tables[2]);
        $this->assertEquals(null,$finder->column('columnB1')->get());
        
        $finder->set($tables[1]);
        $this->assertEquals(null,$finder->column('ss')->get());
        
    }
  
    public function testFindType()
    {
        $schema = $this->getMockComposite();
        $tables = $schema->getChildren();
        $finder = new CompositeFinder();
        
        $finder->set($tables[0]);
        
        $node = $finder->column('columnA2')->type('TypeNodeA')->get();
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Composite\\TypeNode',$node);
        $this->assertEquals('TypeNodeA',$node->getId());
        
    }
    
     public function testFindDatasource()
    {
        $schema = $this->getMockComposite();
        $finder = new CompositeFinder();
        
        $node = $finder->set($schema)->datasource('DatasourceNodeB')->get();
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Composite\\DatasourceNode',$node);
        $this->assertEquals('DatasourceNodeB',$node->getId());
        
    }
    
    
    public function testPathBuilder()
    {
        $builder = new PathBuilder();
        $composite = $this->getMockComposite();
        
        $tables = $composite->getChildren();
        
        $this->assertEquals('schema',$builder->buildPath($composite));
        $this->assertEquals('tableA.schema',$builder->buildPath($tables[0]));
        $this->assertEquals('tableB.schema',$builder->buildPath($tables[1]));
        $this->assertEquals('tableC.schema',$builder->buildPath($tables[2]));
        
        $children = $tables[0]->getChildren();
        
        $this->assertEquals('columnA1.tableA.schema',$builder->buildPath($children[0]));
        $this->assertEquals('columnA2.tableA.schema',$builder->buildPath($children[1]));
        
    }
    

    
}
/* End of File */
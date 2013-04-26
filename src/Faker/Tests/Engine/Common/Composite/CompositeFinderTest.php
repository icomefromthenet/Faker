<?php
namespace Faker\Tests\Engine\Common\Composite;

use Faker\Components\Engine\Common\Composite\CompositeFinder;
use Faker\Components\Engine\Common\Composite\PathBuilder;
use Faker\Tests\Base\AbstractProject;

use Faker\Components\Engine\Common\Composite\SchemaNode;
use Faker\Components\Engine\Common\Composite\TableNode;
use Faker\Components\Engine\Common\Composite\ColumnNode;
use Faker\Components\Engine\Common\Composite\ForeignKeyNode;

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
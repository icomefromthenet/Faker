<?php
namespace Faker\Tests\Engine\Common\Visitor;

use Faker\Tests\Base\AbstractProject;
use Faker\Tests\Engine\Common\Datasource\Mock\MockDatasource;

use Faker\Components\Engine\Common\Visitor\DSourceInjectorVisitor;
use Faker\Components\Engine\Common\Compiler\Graph\DirectedGraph;
use Faker\Components\Engine\Common\Composite\PathBuilder;
use Faker\Components\Engine\DB\Composite\SchemaNode;
use Faker\Components\Engine\DB\Composite\TableNode;
use Faker\Components\Engine\DB\Composite\ColumnNode;
use Faker\Components\Engine\DB\Composite\ForeignKeyNode;
use Faker\Components\Engine\Common\Composite\TypeNode;
use Faker\Components\Engine\Common\Composite\FormatterNode;
use Faker\Components\Engine\Common\Type\FromSource;
use Faker\Components\Engine\Common\Composite\DatasourceNode;
use Faker\Components\Engine\Common\Composite\CompositeFinder;




class DSourceInjectorVisitorTest extends AbstractProject
{

    protected function getComposite()
    {
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema    = new SchemaNode('schema',$event);
        $formatter = $this->getMockBuilder('Faker\Tests\Engine\Common\Formatter\Mock\MockFormatter')->disableOriginalConstructor()->getMock();
        $template_loader  = $this->getMockBuilder('Faker\Components\Templating\Loader')
                           ->disableOriginalConstructor()
                           ->getMock();
        
        
        $tableA    = new TableNode('tableA',$event);
        $columnA1  = new ColumnNode('columnA1',$event);
        $columnA2  = new ColumnNode('columnA2',$event);
        
        $tableB    = new TableNode('tableB',$event);
        $columnB1  = new ColumnNode('columnB1',$event);
        $columnB2  = new ColumnNode('columnB2',$event);
        
        $tableC    = new TableNode('tableC',$event);
        $columnC1  = new ColumnNode('columnC1',$event);
        $columnC2  = new ColumnNode('columnC2',$event);

        $fmNode    = new FormatterNode('fmnode',$event,$formatter);

        # create some datasources and datasource nodes
        $sourceA = new MockDatasource();
        $sourceA->setOption('id','TestSourceA');
        
        $sourceB = new MockDatasource();
        $sourceB->setOption('id','TestSourceB');
        
        $sourceC = new MockDatasource();
        $sourceC->setOption('id','TestSourceC');
        
        $sourceNodeA = new DatasourceNode('SourceNodeA',$event,$sourceA);
        $sourceNodeB = new DatasourceNode('SourceNodeB',$event,$sourceB);
        $sourceNodeC = new DatasourceNode('SourceNodeC',$event,$sourceC);
        
        $schema->addChild($sourceNodeA);
        $schema->addChild($sourceNodeB);
        $schema->addChild($sourceNodeC);
    
    
        # create some fromSource and add to column
        $fromSourceTypeA = new FromSource($template_loader);
        $fromSourceTypeA->setOption('source','TestSourceA');
        
        $fromSourceTypeB = new FromSource($template_loader);
        $fromSourceTypeB->setOption('source','TestSourceB');
        
        $fromSourceTypeC = new FromSource($template_loader);
        $fromSourceTypeC->setOption('source','TestSourceA');
    
        $fromSourceTypeNodeA = new TypeNode('SourceTypeA',$event,$fromSourceTypeA);
        $fromSourceTypeNodeB = new TypeNode('SourceTypeB',$event,$fromSourceTypeB);
        $fromSourceTypeNodeC = new TypeNode('SourceTypeC',$event,$fromSourceTypeC);
        
        
        # relate sourcee types to columns
        $columnA1->addChild($fromSourceTypeNodeA);
        $columnA2->addChild($fromSourceTypeNodeB);
        $columnA2->addChild($fromSourceTypeNodeC);
        
        
        # link tables to schema
        
        $tableC->addChild($columnC1);
        $tableC->addChild($columnC2);
        
        $tableB->addChild($columnB1);
        $tableB->addChild($columnB2);
        
        $tableA->addChild($columnA1);
        $tableA->addChild($columnA2);
        
        $schema->addChild($tableA);
        $schema->addChild($tableB);
        $schema->addChild($tableC);
        $schema->addChild($fmNode);
        
        return $schema;
        
    }


    public function testImplementsBaiscVisitor()
    {
        $composite = $this->getComposite();
        $path      = new PathBuilder();
        $finder    = new CompositeFinder();
        
        $visitor = new DSourceInjectorVisitor($path);
        
        # start the visitor
        $composite->acceptVisitor($visitor);
        
        
        # SourceTypeA
        
        $type = $finder->set($composite)
                         ->table('tableA')->column('columnA1')->type('SourceTypeA')->get();
        
        $typeChildren = $type->getChildren();
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Composite\\DatasourceNode',$typeChildren[0]);
        $this->assertEquals('SourceNodeA',$typeChildren[0]->getId());
        
        # SourceTypeB
        
        $type = $finder->set($composite)
                         ->table('tableA')->column('columnA2')->type('SourceTypeB')->get();
        
        $typeChildren = $type->getChildren();
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Composite\\DatasourceNode',$typeChildren[0]);
        $this->assertEquals('SourceNodeB',$typeChildren[0]->getId());
        
        #SourceTypeC
        
        $type = $finder->set($composite)
                         ->table('tableA')->column('columnA2')->type('SourceTypeC')->get();
        
        $typeChildren = $type->getChildren();
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Composite\\DatasourceNode',$typeChildren[0]);
        $this->assertEquals('SourceNodeA',$typeChildren[0]->getId());         // this third from source requires the SourceNodeA datasource
        
        
        # test that table has injected sources
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Composite\\DatasourceNode',$finder->set($composite)
                         ->table('tableA')->datasource('SourceNodeA')->get()); 
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Composite\\DatasourceNode',$finder->set($composite)
                         ->table('tableA')->datasource('SourceNodeB')->get()); 
        
        $this->assertInstanceOf('Faker\\Components\\Engine\\Common\\Composite\\DatasourceNode',$finder->set($composite)
                         ->table('tableA')->datasource('SourceNodeC')->get()); 
        
    }
    
    
    
    
}
/* End of File */

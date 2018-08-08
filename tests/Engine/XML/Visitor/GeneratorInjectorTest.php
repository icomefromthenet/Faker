<?php
namespace Faker\Tests\Engine\XML\Visitor;

use Faker\Components\Engine\XML\Composite\SchemaNode;
use Faker\Components\Engine\XML\Composite\ColumnNode;
use Faker\Components\Engine\XML\Composite\SelectorNode;
use Faker\Components\Engine\XML\Composite\TableNode;
use Faker\Components\Engine\XML\Composite\TypeNode;
use Faker\Components\Engine\XML\Composite\ForeignKeyNode;
use Faker\Components\Engine\XML\Composite\FormatterNode;
use Faker\Components\Engine\XML\Visitor\GeneratorInjectorVisitor;
use Faker\Tests\Base\AbstractProject;

class GeneratorInjectorTest extends AbstractProject
{

   protected function getComposite()
   {
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $schema    = new SchemaNode('schema',$event);
        $formatter = $this->getMockBuilder('Faker\Tests\Engine\Common\Formatter\Mock\MockFormatter')->disableOriginalConstructor()->getMock();
        
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
        
        $fmNode    = new FormatterNode('fmnode',$event,$formatter);
        
        $fkc1->setOption('foreignTable',$tableA->getId());
        $fkc1->setOption('foreignColumn',$columnA1->getId());
        
        
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
        $schema->addChild($fmNode);
        
        return $schema;
        
    }



    public function testCascadeDefaultGeneratorSet()
    {
        $project = $this->getProject();

        $default_generator = $this->createMock('PHPStats\Generator\GeneratorInterface');
        $factory_mock      = $this->createMock('PHPStats\Generator\GeneratorFactory');
       
        $schema = $this->getComposite();
        
        $visitor = new GeneratorInjectorVisitor($factory_mock,$default_generator);
        $schema->acceptVisitor($visitor);
                
        $tables  = $schema->getChildren();
        
        # assert schema set with default generator
        $this->assertEquals($default_generator,$schema->getGenerator());
        
        
        foreach($tables as $table) {
            if($table instanceOf TableNode ) {
                $this->assertEquals($default_generator,$table->getGenerator());
            }
        }
        
        $columns = $tables[1]->getChildren();
        
        # test columns been set with a default generator        
        $this->assertEquals($default_generator,$columns[0]->getGenerator());
        $this->assertEquals($default_generator,$columns[1]->getGenerator());
       
        
    }

}
/* End of File */
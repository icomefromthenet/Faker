<?php
namespace Faker\Tests\Engine\XML\Visitor;

use Faker\Components\Engine\XML\Composite\SchemaNode;
use Faker\Components\Engine\XML\Composite\ColumnNode;
use Faker\Components\Engine\XML\Composite\SelectorNode;
use Faker\Components\Engine\XML\Composite\TableNode;
use Faker\Components\Engine\XML\Composite\TypeNode;
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
        $builder = $project->getFakerManager()->getCompositeBuilder();

        $default_generator = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $factory_mock      = $this->getMock('PHPStats\Generator\GeneratorFactory');
       
        $schema = $this->getComposite();
        
        $visitor = new GeneratorInjectorVisitor($factory_mock,$default_generator);
        $schema->acceptVisitor($visitor);
                
        $tables  = $schema->getChildren();
        
        $this->assertEquals($default_generator,$table->getGenerator());
        
        
        foreach($tables as $table) {
            if($table instanceOf ) {
                $this->assertEquals($default_generator,$table->getGenerator());
            }
        }
        
        $columns = $tables[0]->getChildren();
        $types   = $columns[0]->getChildren();
        
        $type    = $types[0];
        
        
        $this->assertEquals($default_generator,$type->getGenerator());
       
        
    }



    public function testCascadeSchemaSetting()
    {
        $project = $this->getProject();
        $builder = $project->getFakerManager()->getCompositeBuilder();

        $default_generator = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $new_generator     = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $factory_mock      = $this->getMock('PHPStats\Generator\GeneratorFactory');
        
        $factory_mock->expects($this->once())
                     ->method('create')
                     ->with($this->equalTo('mersenne'))
                     ->will($this->returnValue($new_generator));
        
        $builder->addSchema('schema1',array('randomGenerator' => 'mersenne','generatorSeed' => 100,'name' => 'schema1'))
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100,'name' => 'table1'))
                        ->addColumn('columnA',array('type' => 'string','name' => 'columnA'))
                            ->addType('alphanumeric',array('name' => 'alphanumeric'))
                                ->setTypeOption('format','aaaa')
                            ->end()
                        ->end()
                    ->end()
                ->end();
        
        $schema = $builder->getSchema();
       
        $visitor = new GeneratorInjectorVisitor($factory_mock,$default_generator);
        $schema->acceptVisitor($visitor);
       
        $tables  = $schema->getChildren();
        $columns = $tables[0]->getChildren();
        $types   = $columns[0]->getChildren();
        $type    = $types[0];
        
        $this->assertEquals($new_generator,$type->getGenerator());
        
    }
    
    
    public function testTableOverridesSchema()
    {
        $project = $this->getProject();
        $builder = $project->getFakerManager()->getCompositeBuilder();

        $default_generator = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $new_generator     = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $factory_mock      = $this->getMock('PHPStats\Generator\GeneratorFactory');
        
        $factory_mock->expects($this->once())
                     ->method('create')
                     ->with($this->equalTo('mersenne'))
                     ->will($this->returnValue($new_generator));
        
        $builder->addSchema('schema1',array('name' => 'schema1'))
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100,'randomGenerator' => 'mersenne','generatorSeed' => 100,'name' => 'table1'))
                        ->addColumn('columnA',array('type' => 'string','name' => 'columnA'))
                            ->addType('alphanumeric',array('name' => 'alphanumeric'))
                                ->setTypeOption('format','aaaa')
                            ->end()
                        ->end()
                    ->end()
                ->end();
        
        $schema = $builder->getSchema();
       
        $visitor = new GeneratorInjectorVisitor($factory_mock,$default_generator);
        $schema->acceptVisitor($visitor);
       
        $tables  = $schema->getChildren();
        $columns = $tables[0]->getChildren();
        $types   = $columns[0]->getChildren();
        $type    = $types[0];
        
        $this->assertEquals($new_generator,$type->getGenerator());
        
        
    }
    
    public function testColumnOverrides()
    {
        $project = $this->getProject();
        $builder = $project->getFakerManager()->getCompositeBuilder();

        $default_generator = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $new_generator     = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $factory_mock      = $this->getMock('PHPStats\Generator\GeneratorFactory');
        
        $factory_mock->expects($this->once())
                     ->method('create')
                     ->with($this->equalTo('mersenne'))
                     ->will($this->returnValue($new_generator));
        
        $builder->addSchema('schema1',array('name' => 'schema1'))
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100,'name' => 'table1'))
                        ->addColumn('columnA',array('type' => 'string','randomGenerator' => 'mersenne','generatorSeed' => 100,'name' => 'columnA'))
                            ->addType('alphanumeric',array('name' => 'alphanumeric'))
                                ->setTypeOption('format','aaaa')
                            ->end()
                        ->end()
                    ->end()
                ->end();
        
        $schema = $builder->getSchema();
       
        $visitor = new GeneratorInjectorVisitor($factory_mock,$default_generator);
        $schema->acceptVisitor($visitor);
       
        $tables  = $schema->getChildren();
        $columns = $tables[0]->getChildren();
        $types   = $columns[0]->getChildren();
        $type    = $types[0];
        
        $this->assertEquals($new_generator,$type->getGenerator());
        
        
        
    }
    
    public function testTypeOverrides()
    {
        $project = $this->getProject();
        $builder = $project->getFakerManager()->getCompositeBuilder();

        $default_generator = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $new_generator     = $this->getMock('PHPStats\Generator\GeneratorInterface');
        $factory_mock      = $this->getMock('PHPStats\Generator\GeneratorFactory');
        
        $factory_mock->expects($this->once())
                     ->method('create')
                     ->with($this->equalTo('mersenne'))
                     ->will($this->returnValue($new_generator));
        
        $builder->addSchema('schema1',array('name' => 'schema1'))
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100,'name' => 'table1'))
                        ->addColumn('columnA',array('type' => 'string','name' => 'columnA'))
                            ->addType('alphanumeric',array('randomGenerator' => 'mersenne','generatorSeed' => 100,'name' => 'alphanumeric'))
                                ->setTypeOption('format','aaaa')
                            ->end()
                        ->end()
                    ->end()
                ->end();
        
        $schema = $builder->getSchema();
       
        $visitor = new GeneratorInjectorVisitor($factory_mock,$default_generator);
        $schema->acceptVisitor($visitor);
       
        $tables  = $schema->getChildren();
        $columns = $tables[0]->getChildren();
        $types   = $columns[0]->getChildren();
        $type    = $types[0];
        
        $this->assertEquals($new_generator,$type->getGenerator());
        
        
    }
    
}
/* End of File */
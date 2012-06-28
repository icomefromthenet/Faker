<?php
namespace Faker\Tests\Faker\Visitor;

use Faker\Components\Faker\Visitor\GeneratorInjectorVisitor,
    Faker\Tests\Base\AbstractProject;

class GeneratorInjectorTest extends AbstractProject
{

    public function testCascadeDefaultSetting()
    {
        $project = $this->getProject();
        $builder = $project->getFakerManager()->getCompositeBuilder();

        $default_generator = $this->getMock('Faker\Generator\GeneratorInterface');
        $factory_mock      = $this->getMock('Faker\Generator\GeneratorFactory');
        
        $builder->addSchema('schema1',array())
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100))
                        ->addColumn('columnA',array('type' => 'string'))
                            ->addType('alphanumeric',array())
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
        
        
        $this->assertEquals($default_generator,$type->getGenerator());
       
        
    }



    public function testCascadeSchemaSetting()
    {
        $project = $this->getProject();
        $builder = $project->getFakerManager()->getCompositeBuilder();

        $default_generator = $this->getMock('Faker\Generator\GeneratorInterface');
        $new_generator     = $this->getMock('Faker\Generator\GeneratorInterface');
        $factory_mock      = $this->getMock('Faker\Generator\GeneratorFactory');
        
        $factory_mock->expects($this->once())
                     ->method('create')
                     ->with($this->equalTo('mersenne'))
                     ->will($this->returnValue($new_generator));
        
        $builder->addSchema('schema1',array('randomGenerator' => 'mersenne','generatorSeed' => 100))
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100))
                        ->addColumn('columnA',array('type' => 'string'))
                            ->addType('alphanumeric',array())
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

        $default_generator = $this->getMock('Faker\Generator\GeneratorInterface');
        $new_generator     = $this->getMock('Faker\Generator\GeneratorInterface');
        $factory_mock      = $this->getMock('Faker\Generator\GeneratorFactory');
        
        $factory_mock->expects($this->once())
                     ->method('create')
                     ->with($this->equalTo('mersenne'))
                     ->will($this->returnValue($new_generator));
        
        $builder->addSchema('schema1',array())
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100,'randomGenerator' => 'mersenne','generatorSeed' => 100))
                        ->addColumn('columnA',array('type' => 'string'))
                            ->addType('alphanumeric',array())
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

        $default_generator = $this->getMock('Faker\Generator\GeneratorInterface');
        $new_generator     = $this->getMock('Faker\Generator\GeneratorInterface');
        $factory_mock      = $this->getMock('Faker\Generator\GeneratorFactory');
        
        $factory_mock->expects($this->once())
                     ->method('create')
                     ->with($this->equalTo('mersenne'))
                     ->will($this->returnValue($new_generator));
        
        $builder->addSchema('schema1',array())
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100))
                        ->addColumn('columnA',array('type' => 'string','randomGenerator' => 'mersenne','generatorSeed' => 100))
                            ->addType('alphanumeric',array())
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

        $default_generator = $this->getMock('Faker\Generator\GeneratorInterface');
        $new_generator     = $this->getMock('Faker\Generator\GeneratorInterface');
        $factory_mock      = $this->getMock('Faker\Generator\GeneratorFactory');
        
        $factory_mock->expects($this->once())
                     ->method('create')
                     ->with($this->equalTo('mersenne'))
                     ->will($this->returnValue($new_generator));
        
        $builder->addSchema('schema1',array())
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100))
                        ->addColumn('columnA',array('type' => 'string'))
                            ->addType('alphanumeric',array('randomGenerator' => 'mersenne','generatorSeed' => 100))
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
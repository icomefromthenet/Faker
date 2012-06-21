<?php
namespace Faker\Tests\Faker\Visitor;

use Faker\Components\Faker\Visitor\ColumnCacheInjectorVisitor,
    Faker\Components\Faker\GeneratorCache,
    Faker\Tests\Base\AbstractProject;

class ColumnCacheInjectorTest extends AbstractProject
{

    public function testInjectionGoodIndex()
    {
        $composite =   $this->getComposite();    
        $cache     =   new GeneratorCache();
        
        $visitor   = new ColumnCacheInjectorVisitor($cache,'table1','columnA');
        
        $composite->acceptVisitor($visitor);
        
        $tables = $composite->getChildren();
        $columns = $tables[0]->getChildren();
        $this->assertInstanceOf('Faker\Components\Faker\GeneratorCache',$columns[0]->getGeneratorCache());
        $this->assertTrue($columns[0]->getUseCache());
        
        $tables = $composite->getChildren();
        $columns = $tables[1]->getChildren();
        $this->assertEquals(null,$columns[0]->getGeneratorCache());
        $this->assertFalse($columns[0]->getUseCache());
        
    }
    
    
    protected function getComposite()
    {
        $project = $this->getProject();
        $builder = $project->getFakerManager()->getCompositeBuilder();

        $builder->addSchema('schema1',array())
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100))
                        ->addColumn('columnA',array('type' => 'string'))
                            ->addType('alphanumeric',array())
                                ->setTypeOption('format','aaaa')
                            ->end()
                        ->end()
                    ->end()
                    ->addTable('table2',array('generate' => 100))
                        ->addColumn('columnA',array('type' => 'string'))
                            ->addForeignKey('table2.columnA',array('foreignTable'=> 'table1','foreignColumn' => 'columnA'))
                            ->end()
                        ->end()
                    ->end()
                ->end();
        
        return $builder->getSchema();
        
    }
    
}
/* End of File */
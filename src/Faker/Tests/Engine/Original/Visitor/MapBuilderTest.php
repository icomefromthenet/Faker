<?php
namespace Faker\Tests\Engine\Original\Visitor;

use Faker\Components\Engine\Original\Visitor\MapBuilderVisitor,
    Faker\Components\Engine\Original\GeneratorCache,
    Faker\Components\Engine\Original\Visitor\Relationships,
    Faker\Tests\Base\AbstractProject;

class MapBuilderTest extends AbstractProject
{

    public function testInjectionGoodIndex()
    {
        $composite = $this->getComposite();    
        $visitor   = new MapBuilderVisitor(new Relationships());
        $composite->acceptVisitor($visitor);
        $map = $visitor->getMap();
        $this->assertEquals(1,count($map));
        
        # check that we have 2 relations set
        
        foreach($map as $relationship) {
            $this->assertInstanceOf('\Faker\Components\Engine\Original\Visitor\Relationship',$relationship);
            $this->assertInstanceOf('\Faker\Components\Engine\Original\Visitor\Relation',$relationship->getLocal());
            $this->assertInstanceOf('\Faker\Components\Engine\Original\Visitor\Relation',$relationship->getForeign());
        
            $relationship->getLocal()->getTable();
            $this->assertEquals('table2',$relationship->getLocal()->getTable());
            $this->assertEquals('table1',$relationship->getForeign()->getTable());
            $this->assertEquals('columnA',$relationship->getLocal()->getColumn());
            $this->assertEquals('columnA',$relationship->getForeign()->getColumn());
        }
        
    }
    
    
    protected function getComposite()
    {
        $project = $this->getProject();
        $builder = $project->getFakerManager()->getCompositeBuilder();

        $builder->addSchema('schema1',array('name' => 'schema1'))
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100,'name' => 'table1'))
                        ->addColumn('columnA',array('type' => 'string','name' => 'columnA'))
                            ->addType('alphanumeric',array('name' => 'alphanumeric'))
                                ->setTypeOption('format','aaaa')
                            ->end()
                        ->end()
                    ->end()
                    ->addTable('table2',array('generate' => 100,'name' => 'table2'))
                        ->addColumn('columnA',array('type' => 'string','name' => 'columnA'))
                            ->addForeignKey('table2.columnA',array('foreignTable'=> 'table1','foreignColumn' => 'columnA','name' =>'table2.columnA'))
                            ->end()
                        ->end()
                    ->end()
                ->end();
        
        $builder->merge();
        return $builder->getSchema();
    }
    
}
/* End of File */
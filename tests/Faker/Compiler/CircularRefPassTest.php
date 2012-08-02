<?php
namespace Faker\Tests\Faker\Compiler;

use Faker\Components\Faker\Compiler\Pass\CircularRefPass,
    Faker\Components\Faker\GeneratorCache,
    Faker\Tests\Base\AbstractProject;


class CircularRefPassTest extends AbstractProject
{


    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Circular reference detected
      */
    public function testCircularRefPassBadRef()
    {
        $pass = new CircularRefPass();
        $composite = $this->getComposite();
        
        $pass->process($composite);
        
        
    }
    
    public function testCircularRefPass()
    {
        $pass = new CircularRefPass();
        $composite = $this->getCompositeNoCircularRef();
        
        $pass->process($composite);
        
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
                        ->addColumn('columnB',array('type' => 'string'))
                            ->addForeignKey('table1.columnB',array('foreignTable' => 'table2', 'foreignColumn' => 'columnB'))->end()
                        ->end()
                    ->end()
                    ->addTable('table2',array('generate' => 100))
                        ->addColumn('columnA',array('type' => 'string'))
                            ->addForeignKey('table2.columnA',array('foreignTable'=> 'table1','foreignColumn' => 'columnA'))->end()
                        ->end()
                        ->addColumn('columnB',array('type' => 'string'))
                            ->addType('alphanumeric',array())
                                ->setTypeOption('format','aaaaaa')
                            ->end()
                        ->end()
                    ->end()
                ->end();
        
        $builder->merge();
        return $builder->getSchema();
    }
    
    protected function getCompositeNoCircularRef()
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
                            ->addForeignKey('table2.columnA',array('foreignTable'=> 'table1','foreignColumn' => 'columnA'))->end()
                        ->end()
                    ->end()
                ->end();
        
        $builder->merge();
        return $builder->getSchema();
        
    }

}
/* End of File */
<?php
namespace Faker\Tests\Faker\Compiler;

use Faker\Components\Faker\Compiler\Pass\KeysExistPass,
    Faker\Components\Faker\GeneratorCache,
    Faker\Tests\Base\AbstractProject;


class KeysExistPassTest extends AbstractProject
{


    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage  table2.columnB not found in composite
      */
    public function testCircularRefPassBadRef()
    {
        $pass = new KeysExistPass();
        $composite = $this->getComposite();
        
        $pass->process($composite);
        
        
    }
    
    public function testCircularRefPass()
    {
        $pass = new KeysExistPass();
        $composite = $this->getCompositeAllKeysExist();
        
        $pass->process($composite);
        $this->assertTrue(true);
        
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
                    ->end()
                ->end();
        
        $builder->merge();
        return $builder->getSchema();
    }
    
    protected function getCompositeAllKeysExist()
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
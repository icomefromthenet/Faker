<?php
namespace Faker\Tests\Faker\Compiler;

use Faker\Components\Faker\Compiler\Pass\CircularRefPass,
    Faker\Components\Faker\Compiler\Graph\DirectedGraph,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Visitor\DirectedGraphVisitor,
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
        $graph = $this->buildDirectedGraph($composite);

        $compiler = $this->getMock('Faker\Components\Faker\Compiler\CompilerInterface');
        $compiler->expects($this->once())
                 ->method('getGraph')
                 ->will($this->returnValue($graph));
        
        $pass->process($composite,$compiler);
        
        
    }
    
    public function testCircularRefPass()
    {
        $pass = new CircularRefPass();
        $composite = $this->getCompositeNoCircularRef();
        $graph = $this->buildDirectedGraph($composite);
        
        $compiler = $this->getMock('Faker\Components\Faker\Compiler\CompilerInterface');
        $compiler->expects($this->once())
                 ->method('getGraph')
                 ->will($this->returnValue($graph));
        
        $pass->process($composite,$compiler);
        
    }
        
        
    protected function buildDirectedGraph(CompositeInterface $com)
    {
        $visitor = new DirectedGraphVisitor(new DirectedGraph());
        $com->acceptVisitor($visitor);
        return  $visitor->getDirectedGraph();   
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
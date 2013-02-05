<?php
namespace Faker\Tests\Engine\Original\Visitor;

use Faker\Components\Engine\Original\Visitor\RefCheckVisitor,
    Faker\Tests\Base\AbstractProject;

class RefCheckTest extends AbstractProject
{

    public function testRefCheckonValidIndex()
    {
        $composite =   $this->getComposite();    
        
        $visitor = new RefCheckVisitor('table1','column1');
        
        $composite->acceptVisitor($visitor);
        
        $this->assertInstanceOf('\Faker\Components\Engine\Original\Composite\Column',$visitor->getFoundColumn());
        $this->assertEquals('table1',$visitor->getFoundColumn()->getParent()->getOption('name'));
        $this->assertEquals('column1',$visitor->getFoundColumn()->getOption('name'));
    }
    
    public function testRefCheckonInvalidIndex()
    {
        $composite =   $this->getComposite();    
        $visitor = new RefCheckVisitor('table3','column1');
        $composite->acceptVisitor($visitor);
        $this->assertEquals(null,$visitor->getFoundColumn());
    }
    
    
    protected function getComposite()
    {
        $project = $this->getProject();
        $builder = $project->getFakerManager()->getCompositeBuilder();

        $builder->addSchema('schema1',array('name' => 'schema1'))
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100,'name' => 'table1'))
                        ->addColumn('column1',array('type' => 'string','name' => 'column1'))
                            ->addType('alphanumeric',array('name' => 'alphanumeric'))
                                ->setTypeOption('format','aaaa')
                            ->end()
                        ->end()
                    ->end()
                ->end();
        
        return $builder->build();
        
    }
    
}
/* End of File */
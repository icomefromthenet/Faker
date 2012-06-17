<?php
namespace Faker\Tests\Faker\Visitor;

use Faker\Components\Faker\Visitor\RefCheckVisitor,
    Faker\Tests\Base\AbstractProject;

class RefCheckTest extends AbstractProject
{

    public function testRefCheckonValidIndex()
    {
        $composite =   $this->getComposite();    
        
        $visitor = new RefCheckVisitor('table1','column1');
        
        $composite->acceptVisitor($visitor);
        
        $this->assertInstanceOf('\Faker\Components\Faker\Composite\Column',$visitor->getFoundColumn());
        $this->assertEquals('table1',$visitor->getFoundColumn()->getParent()->getId());
        $this->assertEquals('column1',$visitor->getFoundColumn()->getId());
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

        $builder->addSchema('schema1',array())
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100))
                        ->addColumn('column1',array('type' => 'string'))
                            ->addType('alphanumeric',array())
                                ->setTypeOption('format','aaaa')
                            ->end()
                        ->end()
                    ->end()
                ->end();
        
        return $builder->build();
        
    }
    
}
/* End of File */
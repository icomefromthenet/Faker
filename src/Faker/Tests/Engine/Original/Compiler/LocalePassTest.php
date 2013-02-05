<?php
namespace Faker\Tests\Engine\Original\Compiler;

use Faker\Components\Engine\Original\Compiler\Pass\LocalePass,
    Faker\Components\Engine\Original\Visitor\LocaleVisitor,
    Faker\Tests\Base\AbstractProject;


class LocalePassTest extends AbstractProject
{


    public function testLocalePass()
    {
       $compiler = $this->getMock('Faker\Components\Engine\Original\Compiler\CompilerInterface');
       $project   = $this->getProject();
       $composite = $this->getComposite();
       $visitor   = new LocaleVisitor($project->getLocaleFactory());
       $pass      = new LocalePass($visitor);
    
       $pass->process($composite,$compiler);
       
       $tables  = $composite->getChildren();
       $columns = $tables[0]->getChildren();
       $types   = $columns[0]->getChildren();
        
       $this->assertInstanceOf('Faker\Locale\LocaleInterface',$types[0]->getLocale());
        
       $tables  = $composite->getChildren();
       $columns = $tables[1]->getChildren();
       $types   = $columns[0]->getChildren();
        
       $this->assertInstanceOf('Faker\Locale\LocaleInterface',$types[0]->getLocale());
    }
    
   
        
        
    protected function getComposite()
    {
        $project = $this->getProject();
        $builder = $project->getFakerManager()->getCompositeBuilder();

        $builder->addSchema('schema1',array())
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100))
                        ->addColumn('columnA',array('type' => 'string','name' => 'columnA'))
                            ->addType('alphanumeric')
                                ->setTypeOption('format','aaaa')
                            ->end()
                        ->end()
                    ->end()
                    ->addTable('table2',array('generate' => 100))
                        ->addColumn('columnA',array('type' => 'string','name' => 'columnA'))
                            ->addType('alphanumeric')
                                ->setTypeOption('format','aaaa')
                            ->end()
                        ->end()
                    ->end()
                ->end();
        
        $builder->merge();
        return $builder->getSchema();
    }
    
}
/* End of File */
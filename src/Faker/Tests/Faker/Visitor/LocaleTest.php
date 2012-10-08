<?php
namespace Faker\Tests\Faker\Visitor;

use Faker\Components\Faker\Visitor\LocaleVisitor,
    Faker\Tests\Base\AbstractProject;

class LocaleTest extends AbstractProject
{

    public function testInjectionGoodIndex()
    {
        $project        = $this->getProject();
        $composite      = $this->getComposite();    
        $locale_factory = $project->getLocaleFactory();
        $visitor        = new LocaleVisitor($locale_factory);
        
        $composite->acceptVisitor($visitor);
        
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

        $builder->addSchema('schema1',array('locale' => 'en','name' => 'schema1'))
                    ->addWriter('mysql','sql')
                    ->addTable('table1',array('generate' => 100,'name' => 'table1'))
                        ->addColumn('columnA',array('type' => 'string'))
                            ->addType('alphanumeric',array('name' => 'alphanumeric'))
                                ->setTypeOption('format','aaaa')
                            ->end()
                        ->end()
                    ->end()
                    ->addTable('table2',array('generate' => 100,'name' => 'table2'))
                        ->addColumn('columnA',array('type' => 'string','name' => 'columnA'))
                            ->addType('alphanumeric',array('name' => 'alphanumeric'))
                                ->setTypeOption('format','aaaa')
                            ->end()
                        ->end()
                    ->end()
                ->end();
        
        return $builder->getSchema();
        
    }
    
}
/* End of File */
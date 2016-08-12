<?php
namespace Faker\Tests\Engine\XML\Compiler;

use Faker\Components\Engine\XML\Compiler\Pass\LocalePass;
use Faker\Components\Engine\XML\Visitor\LocaleVisitor;
use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Composite\CompositeException;


class LocalePassTest extends AbstractProject
{


    public function testLocalePass()
    {
       $compiler = $this->getMock('Faker\Components\Engine\Common\Compiler\CompilerInterface');
       $project   = $this->getProject();
       $composite = $this->getComposite();
       $localeFactory = $project->getLocaleFactory();
       
       $visitor   = new LocaleVisitor($localeFactory);
       $pass      = new LocalePass($visitor);
    
       $pass->process($composite,$compiler);
       
       $tables  = $composite->getChildren();
       $columns = $tables[1]->getChildren();
       $types   = $columns[0]->getChildren();
       
       
       $this->assertInstanceOf('Faker\Locale\LocaleInterface',$types[0]->getLocale());
       $this->assertInstanceOf('Faker\Locale\LocaleInterface',$columns[0]->getLocale());
       $this->assertInstanceOf('Faker\Locale\LocaleInterface',$tables[1]->getLocale());
       $this->assertInstanceOf('Faker\Locale\LocaleInterface',$composite->getLocale());

       $tables  = $composite->getChildren();
       $columns = $tables[2]->getChildren();
       $types   = $columns[0]->getChildren();
        
       $this->assertInstanceOf('Faker\Locale\LocaleInterface',$types[0]->getLocale());
       $this->assertInstanceOf('Faker\Locale\LocaleInterface',$columns[0]->getLocale());
       $this->assertInstanceOf('Faker\Locale\LocaleInterface',$tables[1]->getLocale());
       $this->assertInstanceOf('Faker\Locale\LocaleInterface',$composite->getLocale());

    }
    
   
        
        
    protected function getComposite()
    {
        $project = $this->getProject();
        $builder = $project->getXMLEngineBuilder();

        $builder->addSchema('schema1',array('locale'=>'en'))
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
        
        $builder->validate();
        
        return $builder->getSchema();
    }
    
}
/* End of File */
<?php
namespace Faker\Tests\Faker\Formatter;

use Faker\Components\Faker\Builder,
    Faker\Components\Faker\Formatter\GenerateEvent,
    Faker\Components\Faker\Formatter\FormatterInterface,
    Doctrine\DBAL\Platforms\MySqlPlatform,
    Faker\Components\Faker\Formatter\Sql,
    Faker\Tests\Base\AbstractProject;

class SqlTest extends AbstractProject
{
    
    
    protected function getBuilderWithBasicComposite()
    {
         # build a composite for this formatter 
        $project = $this->getProject();
        $builder = $project['faker_manager']->getCompositeBuilder();
       
        $builder->addSchema('schema_1',array('name' =>'schema_1'))
                    ->addTable('table_1',array('generate' => 1000,'name' =>'table_1'))
                        ->addColumn('column_1',array('type' => 'text','name' =>'column_1'))
                            ->addType('alphanumeric',array('name' => 'alphanumeric'))
                                ->setTypeOption('format','Ccccc')
                            ->end()
                        ->end()
                        ->addColumn('column_2',array('type' => 'integer','name' =>'column_2'))
                            ->addType('alphanumeric',array('name' => 'alphanumeric'))
                                ->setTypeOption('format','Ccccc')
                            ->end()
                        ->end()
                    ->end()
                    ->addWriter('mysql','sql')
                ->end();
            
        
        return $builder->build();
        
    }
    
    
    
    
    
    protected $formatter_mock;
    
    
    
    public function setUp()
    {
        $event      = $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $writer     = $this->getMockBuilder('Faker\Components\Writer\WriterInterface')->setMethods(array('getStream','flush','write'))->getMock();
        $platform   =  new MySqlPlatform();
        $formatter  =  new Sql($event,$writer,$platform);
    
        $this->formatter_mock = $formatter; 
        
        $writer   = $this->getMockBuilder('Faker\Components\Writer\WriterInterface')->setMethods(array('getStream','flush','write'))->getMock();
        $stream   = $this->getMockBuilder('Faker\Components\Writer\Stream')->disableOriginalConstructor()->getMock();
        $limit    = $this->getMockBuilder('Faker\Components\Writer\Limit')->disableOriginalConstructor()->getMock();
        $sequence = $this->getMockBuilder('Faker\Components\Writer\Sequence')->disableOriginalConstructor()->getMock();
       
        
        $stream->expects($this->any())
                 ->method('getLimit')
                 ->will($this->returnValue($limit));
        
        $stream->expects($this->any())
               ->method('getSequence')
               ->will($this->returnValue($sequence));
        
        $writer->expects($this->any())
               ->method('getStream')
               ->will($this->returnValue($stream));
               
        $encoding = $this->getMockBuilder('Faker\Components\Writer\Encoding')
                    ->disableOriginalConstructor()
                    ->getMock();
                    
                       
        $encoding->expects($this->any())
                 ->method('encode')
                 ->will($this->returnArgument(0));       
        
        $stream->expects($this->any())
                ->method('getEncoder')
                ->will($this->returnValue($encoding));
        
        $this->formatter_mock->setWriter($writer);
        
        parent::setUp();
    }
    
    
    /**
      *   
      */
    public function testonSchemaStart()
    {
        # mock the writer dep 
        
        $this->formatter_mock->getWriter()->getStream()->getSequence()->expects($this->once())
                 ->method('setPrefix')
                 ->with('schema_1');
        
        $this->formatter_mock->getWriter()->getStream()->getSequence()->expects($this->once())
                 ->method('setSuffix')
                 ->with('mysql');
        
        $this->formatter_mock->getWriter()->getStream()->getSequence()->expects($this->once())
                 ->method('setExtension')
                 ->with('sql');                           
        
        $header_template = $this->getMockBuilder('Faker\Components\Templating\Template')->disableOriginalConstructor()->getMock();

        $header_template->expects($this->once())
                        ->method('setData')
                        ->with($this->isType('array'));

        $this->formatter_mock->getWriter()->getStream()->expects($this->once())
            ->method('getHeaderTemplate')
            ->will($this->returnValue($header_template));
        
        $this->formatter_mock->merge();
        $generate_event    = new GenerateEvent($this->getBuilderWithBasicComposite(),array(),'schema_1');
        $this->formatter_mock->onSchemaStart($generate_event);
        
    }
    
   
    
    /**
      *  @depends  testonSchemaStart
      */
    public function testonTableStart()
    {
        $this->formatter_mock->getWriter()->getStream()->getSequence()->expects($this->once())
                                                           ->method('setBody')
                                                           ->with('table_1');
                                                           
        $this->formatter_mock->getWriter()->expects($this->exactly(10))->method('write');                                                   
        
        $this->formatter_mock->merge();
                                                           
        $composite   = $this->getBuilderWithBasicComposite();
        $tables      = $composite->getChildren();
       
        $generate_event    = new GenerateEvent($tables[0],array(),$tables[0]->getOption('name'));
       
        $out = $this->formatter_mock->onTableStart($generate_event);
       
        
    }
    
    
   
    /**
      *  @depends testonTableStart 
      */
    public function testonRowStart()
    {
        $this->formatter_mock->merge();
        $composite   = $this->getBuilderWithBasicComposite();
        $tables    = $composite->getChildren();
        
        $generate_event    = new GenerateEvent($tables[0],array(),'row_1');
        $this->assertEquals($this->formatter_mock->onRowStart($generate_event),null);
        
    }
    
    
    /**
      *  @depends  testonRowStart
      */
    public function testonRowEnd()
    {
        $this->formatter_mock->merge();
        $this->formatter_mock->getWriter()->expects($this->any())
               ->method('write')
               ->with($this->isType('string'));
        
        $composite   = $this->getBuilderWithBasicComposite();
        $tables      = $composite->getChildren();

        $generate_event_row      = new GenerateEvent($tables[0],array(
                                                      'column_1' => 'a first value',
                                                      'column_2' => 5
                                                      ),'row_1');
        
        # need a column map (normally be done in on Row start event)
        $map = array();
        foreach($tables[0]->getChildren() as $column) {
            $map[$column->getOption('name')] = $column->getColumnType();
        }
        
        $this->formatter_mock->setColumnMap($map);
        $look = $this->formatter_mock->onRowEnd($generate_event_row);
        $this->assertContains("INSERT INTO `table_1` (`column_1`,`column_2`) VALUES ('a first value',5);",$look);
        
    }
    
   
    /**
      *  @depends  testonRowEnd
      */
    public function testonTableEnd()
    {
        $this->formatter_mock->merge();
        $this->formatter_mock->getWriter()->expects($this->once())
                               ->method('Flush');    
    
        $composite   = $this->getBuilderWithBasicComposite();
        $tables    = $composite->getChildren();

        $generate_event = new GenerateEvent($tables[0],array(),$tables[0]->getOption('name'));
        $this->formatter_mock->onTableEnd($generate_event);
        
        # was column map nulled
        $this->assertEquals(null,$this->formatter_mock->getColumnMap());
    }
    
    
   
     /**
      *  @depends  testonTableEnd
      */
    public function testonSchemaEnd()
    {
        $this->formatter_mock->merge();
        $this->formatter_mock->getWriter()->expects($this->once())
                               ->method('Flush');
        
        $generate_event    = new GenerateEvent($this->getBuilderWithBasicComposite(),array(),'schema_1');
        $this->formatter_mock->onSchemaEnd($generate_event);
    }
    
    
}
/* End of File */
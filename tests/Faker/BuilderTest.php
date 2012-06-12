<?php
namespace Faker\Tests\Faker;

use Faker\Components\Faker\Builder,
    Faker\Tests\Base\AbstractProject;
    Faker\Components\Faker\Type\Type;

    
class BuilderTest extends AbstractProject
{
    
    public function testNewBuilder()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $this->assertInstanceOf('Faker\Components\Faker\Builder',$builder);
    }
   
    //  -------------------------------------------------------------------------
    # Schema Tests
    
    public function testAddSchema()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $id = 'myschema';
        $builder->addSchema($id,array())->end();
   
        $this->assertTrue(true);     
    }
    
    
    public function testAddWritter()
    {
        
        $formatter ='sql';
        $platform  = 'test';
        
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
       
        $platform_instance   = $this->getMockBuilder('Doctrine\DBAL\Platforms\AbstractPlatform')->disableOriginalConstructor()->getMockForAbstractClass();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        
        $platform_factory->expects($this->once())
                         ->method('create')
                         ->with($this->equalTo($platform))
                         ->will($this->returnValue($platform_instance));
       
       
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
      
        $formatter_sql       = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterInterface')->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
        $formatter_factory->expects($this->once())
                          ->method('create')
                          ->with($this->equalTo($formatter),$this->equalTo($platform_instance))
                          ->will($this->returnValue($formatter_sql));
                          
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('myschema',array())
                    ->addWriter($platform,$formatter)
                ->end();
        
    
    }
    
    
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Schema must have at least 1 table
      */
    public function testBuildScheamNoTableThrowsException()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $id = 'myschema';
        $builder->addSchema($id,array())->end();
        
        $builder->build();     
    }
    
     /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Schema must have a name
      */
    public function testAddSchemaEmptyNameException()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('',array())->end();
        
    }
    
     /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Scheam already added only have one
      */
    public function testAddSecondSchemaException()
    {
        
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('table1',array())->addSchema('table1',array());
        
        
    }
    
    //  -------------------------------------------------------------------------
    # Table Tests
    
    public function testAddTable()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $id = 'myschema';
        $builder->addSchema($id,array())
                    ->addTable('table1',array('generate' => 100))
                    ->end()
                ->end();
        
        $this->assertTrue(true);
         
    }
    
    
    
    //  -------------------------------------------------------------------------
    # Column Test
    
    public function testColumnAdd()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
     
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
     
        $column_type_factory->expects($this->once())
                            ->method('create')
                            ->with($this->equalTo('integer'))
                            ->will($this->returnValue($column_type));
    
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addTable('table_1',array('generate' => 100))
                        ->addColumn('column_1',array('type'=>'integer'))
                        ->end()
                    ->end()
                ->end();
    }
    
    //  -------------------------------------------------------------------------
    # Test Selectors
    
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Can not add new Selector without first setting a table, schema and column
      */
    public function testSelectorExceptionNoSchema()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
        
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSelector('alternate',array())->end();
        
    }
    
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Can not add new Selector without first setting a table, schema and column
      */
    public function testSelectorExceptionNoTable()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
    
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addSelector('alternate',array())
                    ->end()
                ->end();
        
    }

    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Can not add new Selector without first setting a table, schema and column
      */
    public function testSelectorExceptionNoColumn()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
    
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addTable('table_1',array('generate' =>100))
                        ->addSelector('alternate',array())
                        ->end()
                    ->end()
                ->end();
    }

    
   
    
    public function testSelectorAdd()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
     
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
     
        $column_type_factory->expects($this->once())
                            ->method('create')
                            ->with($this->equalTo('integer'))
                            ->will($this->returnValue($column_type));
    
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addTable('table_1',array('generate' => 100))
                        ->addColumn('column_1',array('type'=>'integer'))
                            ->addSelector('alternate',array('step' => 1))
                            ->end()
                        ->end()
                    ->end()
                ->end();
    }
   
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Unknown Selector a
      */
    public function testSelectorNotExist()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
     
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
     
        $column_type_factory->expects($this->once())
                            ->method('create')
                            ->with($this->equalTo('integer'))
                            ->will($this->returnValue($column_type));
    
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addTable('table_1',array('generate' => 100))
                        ->addColumn('column_1',array('type'=>'integer'))
                            ->addSelector('a',array())
                            ->end()
                        ->end()
                    ->end()
                ->end();
    }
    
     /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Alternate type needs step
      */
    public function testAlternateStepNotSupplied()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
     
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
     
        $column_type_factory->expects($this->once())
                            ->method('create')
                            ->with($this->equalTo('integer'))
                            ->will($this->returnValue($column_type));
    
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addTable('table_1',array('generate' => 100))
                        ->addColumn('column_1',array('type' => 'integer'))
                            ->addSelector('alternate',array())
                            ->end()
                        ->end()
                    ->end()
                ->end();
    }
    
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Pick type needs a probability
      */
    public function testPickProbabilityNotSupplied()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
     
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
     
        $column_type_factory->expects($this->once())
                            ->method('create')
                            ->with($this->equalTo('integer'))
                            ->will($this->returnValue($column_type));
    
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addTable('table_1',array('generate' => 100))
                        ->addColumn('column_1',array('type' =>'integer'))
                            ->addSelector('pick',array())
                            ->end()
                        ->end()
                    ->end()
                ->end();
    }
   

    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage When type must have a swap parent
      */
    public function testWhenNoSwap()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
     
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
     
        $column_type_factory->expects($this->once())
                            ->method('create')
                            ->with($this->equalTo('integer'))
                            ->will($this->returnValue($column_type));
    
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addTable('table_1',array('generate' => 100))
                        ->addColumn('column_1',array('type'=>'integer'))
                            ->addSelector('when',array())
                            ->end()
                        ->end()
                    ->end()
                ->end();
    }
  
    
    
 
    public function testAddWhenAndSwap()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
     
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
     
        $column_type_factory->expects($this->once())
                            ->method('create')
                            ->with($this->equalTo('integer'))
                            ->will($this->returnValue($column_type));
    
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addTable('table_1',array('generate' => 100))
                        ->addColumn('column_1',array('type'=>'integer'))
                            ->addSelector('swap',array())
                                ->addSelector('when',array('switch' => 1))
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();
        
        
     }
    
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMesssage When type must have a switch value
      */
    public function testAddWhenAndSwapWithNoSwitchOption()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
     
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
     
        $column_type_factory->expects($this->once())
                            ->method('create')
                            ->with($this->equalTo('integer'))
                            ->will($this->returnValue($column_type));
    
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addTable('table_1',array('generate' => 100))
                        ->addColumn('column_1',array('type'=>'integer'))
                            ->addSelector('swap',array())
                                ->addSelector('when',array())
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();
        
        
     }
   
    //  -------------------------------------------------------------------------
    # Test Types
    
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Can not add new Selector without first setting a table and schema or column
      */
    public function testTypeExceptionNoSchema()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
        
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addType('alternate',array())->end();
        
    }
    
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Can not add new Selector without first setting a table and schema or column
     */
    public function testTypeExceptionNoTable()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
    
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addType('alphanumeric',array())
                    ->end()
                ->end();
        
    }

    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Can not add new Selector without first setting a table and schema or column
      */
    public function testTypeExceptionNoColumn()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
    
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
    
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addTable('table_1',array('generate' => 100))
                        ->addType('alternate',array())
                        ->end()
                    ->end()
                ->end();
    }
    
    
    
    
   
    public function testAddTypeToColumn()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
     
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
     
        $column_type_factory->expects($this->once())
                            ->method('create')
                            ->with($this->equalTo('integer'))
                            ->will($this->returnValue($column_type));
    
        $type_mock           = $this->getMockBuilder('Faker\Components\Faker\Type\Type')->disableOriginalConstructor()->getMockForAbstractClass();   
        
        $type_mock->expects($this->any())
                  ->method('getParent')
                  ->will($this->returnValue($type_mock));
        
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        
        $type_factory->expects($this->once())
                     ->method('create')
                     ->with($this->equalto('alphanumeric'),$this->isInstanceOf('Faker\Components\Faker\Composite\Column'))
                     ->will($this->returnValue($type_mock));
                     
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
        
            
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addTable('table_1',array('generate' => 100))
                        ->addColumn('column_1',array('type'=>'integer'))
                            ->addType('alphanumeric',array())
                            ->end()
                        ->end()
                    ->end()
                ->end();
    
    }
    
    public function testAddTypeToSelector()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
     
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
     
        $column_type_factory->expects($this->once())
                            ->method('create')
                            ->with($this->equalTo('integer'))
                            ->will($this->returnValue($column_type));
    
        $type_mock           = $this->getMockBuilder('Faker\Components\Faker\Type\Type')->disableOriginalConstructor()->getMockForAbstractClass();   
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        
        $type_factory->expects($this->exactly(2))
                     ->method('create')
                     ->with($this->equalto('alphanumeric'),$this->isInstanceOf('Faker\Components\Faker\Composite\Random'))
                     ->will($this->returnValue($type_mock));
                     
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
        
            
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addTable('table_1',array('generate' => 100))
                        ->addColumn('column_1',array( 'type' =>'integer'))
                            ->addSelector('random',array())
                                ->addType('alphanumeric',array())
                                ->end()
                                ->addType('alphanumeric',array())
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();
    }
   
    public function testAddTypeToSwapSelector()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
     
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
     
        $column_type_factory->expects($this->once())
                            ->method('create')
                            ->with($this->equalTo('integer'))
                            ->will($this->returnValue($column_type));
    
        $type_mock           = $this->getMockBuilder('Faker\Components\Faker\Type\Type')->disableOriginalConstructor()->getMockForAbstractClass();   
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        
        $type_factory->expects($this->exactly(1))
                     ->method('create')
                     ->with($this->equalto('alphanumeric'),$this->isInstanceOf('Faker\Components\Faker\Composite\When'))
                     ->will($this->returnValue($type_mock));
                     
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
        
            
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addTable('table_1',array('generate' => 100))
                        ->addColumn('column_1',array('type' =>'integer'))
                            ->addSelector('swap',array())
                                ->addSelector('when',array('switch' => 10))
                                    ->addType('alphanumeric',array())
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();
    }
    
    
    
     
    
    /**
      *  @expectedException Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Type has not been set, can not accept option op1
      */    
    public function testTypeSetOptionNoType()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
     
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
     
        $column_type_factory->expects($this->once())
                            ->method('create')
                            ->with($this->equalTo('integer'))
                            ->will($this->returnValue($column_type));
    
        $type_factory        = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')->disableOriginalConstructor()->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
        
            
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addTable('table_1',array('generate' => 100))
                        ->addColumn('column_1',array( 'type' =>'integer'))
                            ->addSelector('swap',array())
                                ->addSelector('when',array('switch' => 10))
                                    ->setTypeOption('op1','po2')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();
    }
    
   
    
    public function testTypeSetOption()
    {
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                                    ->getMock();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')
                            ->disableOriginalConstructor()
                            ->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        $column_type_factory->expects($this->once())
                            ->method('create')
                            ->with($this->equalTo('integer'))
                            ->will($this->returnValue($column_type));
    
        $type_mock  = $this->getMockBuilder('Faker\Components\Faker\Type\Type')
                            ->disableOriginalConstructor()
                            ->setMethods('setOption')
                            ->getMockForAbstractClass();
        
        # fails for unknow reasons, could be problem with mocking (DBunit)
       /* $type_mock->expects($this->once())
                  ->method('setOption')
                  ->with($this->equalTo('op1'),$this->equalTo('op2'))
                  ->will($this->returnValue(true)); */
        
        $type_factory = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')
                            ->disableOriginalConstructor()
                            ->getMock();   
          
        $type_factory->expects($this->once())
                     ->method('create')
                     ->with($this->equalto('alphanumeric'),$this->isInstanceOf('Faker\Components\Faker\Composite\When'))
                     ->will($this->returnValue($type_mock));
                     
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')
                                    ->disableOriginalConstructor()
                                    ->getMock(); 
            
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       

        $builder->addSchema('schema_1',array())
                    ->addTable('table_1',array('generate' => 100))
                        ->addColumn('column_1',array('type' =>'integer'))
                            ->addSelector('swap',array())
                                ->addSelector('when',array('switch' => 10))
                                    ->addType('alphanumeric',array())
                                        ->setTypeOption('op1','op2')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();
    }
   
    
    
    //  -------------------------------------------------------------------------
    # Test Build
    
    public function testBuild()
    {
        
        $formatter ='sql';
        $platform  = 'test';
        
       
        $platform_instance   = $this->getMockBuilder('Doctrine\DBAL\Platforms\AbstractPlatform')->disableOriginalConstructor()->getMockForAbstractClass();
        $platform_factory    = $this->getMockBuilder('Faker\PlatformFactory')->disableOriginalConstructor()->getMock();
        $platform_factory->expects($this->any())
                         ->method('create')
                         ->with($this->equalTo($platform))
                         ->will($this->returnValue($platform_instance));
       
        $formatter_sql       = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterInterface')->getMock();        
        $formatter_factory   = $this->getMockBuilder('Faker\Components\Faker\Formatter\FormatterFactory')->disableOriginalConstructor()->getMock(); 
        $formatter_factory->expects($this->any())
                          ->method('create')
                          ->with($this->equalTo('sql'),$this->isInstanceOf('Doctrine\DBAL\Platforms\AbstractPlatform'))
                          ->will($this->returnValue($formatter_sql));
                          
        $event               = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $column_type_factory = $this->getMockBuilder('Faker\ColumnTypeFactory')->disableOriginalConstructor()->getMock();
     
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
        $column_type_factory->expects($this->any())
                            ->method('create')
                            ->with($this->equalTo('integer'))
                            ->will($this->returnValue($column_type));
    
        $type_mock  = $this->getMockBuilder('Faker\Components\Faker\Type\Type')
                            ->setMethods(array('setOption'))
                            ->disableOriginalConstructor()
                            ->getMockForAbstractClass();   
        
        
        $type_factory = $this->getMockBuilder('Faker\Components\Faker\TypeFactory')
                            ->disableOriginalConstructor()
                            ->getMock();        
        
        $type_factory->expects($this->any())
                     ->method('create')
                     ->with($this->equalto('alphanumeric'),$this->isInstanceOf('Faker\Components\Faker\Composite\When'))
                     ->will($this->returnValue($type_mock));
                     
            
        $builder = new Builder($event,$platform_factory,$column_type_factory,$type_factory,$formatter_factory);       
        
        $builder->addSchema('schema_1',array())
                    ->addWriter($platform,$formatter)
                    ->addTable('table_1',array('generate' => 100))
                        ->addColumn('column_1',array('type' => 'integer'))
                            ->addSelector('swap',array())
                                ->addSelector('when',array('switch' => 10))
                                    ->addType('alphanumeric',array())
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();
    
        $schema = $builder->build();    
     
        # test that schema details have been set
        
        $this->assertInstanceOf('Faker\Components\Faker\Composite\Schema',$schema);
     
        # test that all children of schema are tables
        
        $this->assertEquals(count($schema),1);
        foreach($schema->getChildren() as $child) {
            $this->assertInstanceOf('Faker\Components\Faker\Composite\Table',$child);
        }
        
        # test that table has a column
        
        $table = $schema->getChildren();
        $table = $table[0];
        
        $this->assertEquals(count($table->getChildren()),1);
        
        foreach($table->getChildren() as $child) {
            $this->assertInstanceOf('Faker\Components\Faker\Composite\Column',$child);
        }
        
        # test that column has selector
        
        $column = $table->getChildren();
        $column = $column[0];
        
        $this->assertEquals(count($column->getChildren()),1);
        
        foreach($column->getChildren() as $child) {
            $this->assertInstanceOf('Faker\Components\Faker\Composite\Swap',$child);
        }
        
        # test that selector has a selector
        $selector = $column->getChildren();
        $selector = $selector[0];
        
        $this->assertEquals(count($selector->getChildren()),1);
        
        foreach($selector->getChildren() as $child) {
            $this->assertInstanceOf('Faker\Components\Faker\Composite\When',$child);
        }
        
        # test that inner selector has value
        $selector_inner = $selector->getChildren();
        $selector_inner = $selector_inner[0];
        
        $this->assertEquals(count($selector_inner->getChildren()),1);
        
        foreach($selector_inner->getChildren() as $child) {
            $this->assertInstanceOf('Faker\Components\Faker\Type\Type',$child);
        }
        
    }
    
}
/* End of File */
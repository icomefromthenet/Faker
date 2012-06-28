<?php
namespace Faker\Tests\Faker\Composite;

use Faker\Components\Faker\Composite\Column,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Formatter\FormatEvents,
    Faker\Components\Faker\Formatter\GenerateEvent,
    Faker\Components\Faker\GeneratorCache,
    Faker\Components\Faker\CacheInterface,
    Doctrine\DBAL\Types\Type as ColumnType,
    Faker\Tests\Base\AbstractProject;
    
class ColumnTest extends AbstractProject
{
    
    public function testImplementsCompositeInterface()
    {
        $id = 'table_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
      
        $column = new Column($id,$parent,$event,$column_type);
        
        $this->assertInstanceOf('Faker\Components\Faker\Composite\CompositeInterface',$column);
        $this->assertInstanceOf('Faker\Components\Faker\CacheInterface',$column);
    
    }
    
    public function testSchemaDispatchesEvent()
    {
        $id = 'table_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
      
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
 
        $event->expects($this->exactly(3))
              ->method('dispatch')
              ->with($this->logicalOr(
                        $this->stringContains(FormatEvents::onColumnStart),
                        $this->stringContains(FormatEvents::onColumnGenerate),
                        $this->stringContains(FormatEvents::onColumnEnd)
                        ),
                        $this->isInstanceOf('\Faker\Components\Faker\Formatter\GenerateEvent'));
       
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->once())
                ->method('generate')
                ->with($this->equalTo(1),$this->isType('array'))
                ->will($this->returnValue('example'));
       
              
        $column = new Column($id,$parent,$event,$column_type);
        $column->addChild($child_a);
        $column->generate(1,array());
        
    }
    
    
    
    public function testChildrenGenerateCalled()
    {
        $id = 'table_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $column = new Column($id,$parent,$event,$column_type,array('locale'=>null));
             
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_a->expects($this->exactly(1))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('example'));
            
       
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->exactly(1))
                ->method('generate')
                ->with($this->isType('integer'),$this->isType('array'))
                ->will($this->returnValue('example'));
        
        $column->addChild($child_a);        
        $column->addChild($child_b);        
        
        $column->merge();
        $column->validate();
        
        
        $column->generate(100,array());
        
        # assert default locale
        $this->assertEquals($column->getOption('locale'),'en');
   
    }
    
    public function testConfigurationParsed()
    {
        $id = 'table_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $column = new Column($id,$parent,$event,$column_type,array('locale'=> 'china'));
        
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();     
        $child_a->expects($this->exactly(1))
                ->method('validate')
                ->will($this->returnValue(true));
            
       
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->exactly(1))
                ->method('validate')
                ->will($this->returnValue(true));
        
        $column->addChild($child_a);        
        $column->addChild($child_b);        
        
        $column->validate();
        
        $this->assertEquals($column->getOption('locale'),'china');
   
    }
    
    
    public function testToXml()
    {
        $id = 'column_1';
        $rows_generate = 100;
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
        $column_type->expects($this->once())
                    ->method('getName')
                    ->will($this->returnValue('type'));
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $column = new Column($id,$parent,$event,$column_type);
     
        
        $xml = $column->toXml();
        $this->assertContains('<column name="column_1" type="type">',$xml );
        $this->assertContains('</column>',$xml);
    
    }
    
    
    public function testProperties()
    {
        $id = 'column_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $column = new Column($id,$parent,$event,$column_type);
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $column->addChild($child_a);        
        $column->addChild($child_b);        
        
        $this->assertEquals($column->getChildren(),array($child_a,$child_b));
        $this->assertSame($column->getEventDispatcher(),$event);
        $this->assertEquals($parent,$column->getParent());
        $this->assertEquals($id,$column->getId());
        $this->assertSame($column_type,$column->getColumnType());
    }
    
    public function testCacheInterface()
    {
         $id = 'table_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $column = new Column($id,$parent,$event,$column_type,array('locale'=> 'china'));
        
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();     
        $child_a->expects($this->exactly(1))
                ->method('validate')
                ->will($this->returnValue(true));
            
       
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->exactly(1))
                ->method('validate')
                ->will($this->returnValue(true));
        
        $column->addChild($child_a);        
        $column->addChild($child_b);        
        
        $column->validate();
                
        
        # test the default use cache = false
        $this->assertFalse($column->getUseCache());

        # test the use cache property
        $column->setUseCache(true);
        $this->assertTrue($column->getUseCache());
        
        # setup cache (not testing validation)
        $cache = new GeneratorCache();
        $column->setGeneratorCache($cache);
        $this->assertSame($column->getGeneratorCache(),$cache);
                
        
    }
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Column has been told to use cache but none set
      */
    public function testCacheValidateFailsMissingCache()
    {
          $id = 'table_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $column = new Column($id,$parent,$event,$column_type,array('locale'=> 'china'));
        
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();     
        $child_a->expects($this->exactly(1))
                ->method('validate')
                ->will($this->returnValue(true));
            
       
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->exactly(1))
                ->method('validate')
                ->will($this->returnValue(true));
        
        $column->addChild($child_a);        
        $column->addChild($child_b);        
        
        # test the default use cache = false
        $this->assertFalse($column->getUseCache());

        # test the use cache property
        $column->setUseCache(true);
        $this->assertTrue($column->getUseCache());
        
        $column->validate();
        
    }
    
    public function testValidatePassesWithCacheUsed()
    {
         $id = 'table_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $column = new Column($id,$parent,$event,$column_type,array('locale'=> 'china'));
        
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();     
        $child_a->expects($this->exactly(1))
                ->method('validate')
                ->will($this->returnValue(true));
            
       
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->exactly(1))
                ->method('validate')
                ->will($this->returnValue(true));
        
        $column->addChild($child_a);        
        $column->addChild($child_b);        
        
        # test the use cache property
        $column->setUseCache(true);
        
        # setup cache (not testing validation)
        $cache = new GeneratorCache();
        $column->setGeneratorCache($cache);
        
        $column->validate();        
        
    }
    
    
    public function testCacheUsedWithGenerate()
    {
         $id = 'table_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $column = new Column($id,$parent,$event,$column_type,array('locale'=> 'china'));
        
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();     
        $child_a->expects($this->exactly(1))
                ->method('validate')
                ->will($this->returnValue(true));
        
        $child_a->expects($this->exactly(2))
                ->method('generate')
                ->will($this->returnValue('valuea'));
            
       
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b->expects($this->exactly(1))
                ->method('validate')
                ->will($this->returnValue(true));
                
        $child_b->expects($this->exactly(2))
                ->method('generate')
                ->will($this->returnValue('valueb'));        
        
        $column->addChild($child_a);        
        $column->addChild($child_b);        
        
        $column->setUseCache(true);
        $cache = new GeneratorCache();
        $column->setGeneratorCache($cache);
        
        $column->validate(); 
        
        $column->generate(1,array());
        $column->generate(2,array());
        
        $this->assertEquals(2,count($cache));
        $this->assertEquals($cache->current(),'valueavalueb');
        
        $cache->next();
        $this->assertEquals($cache->current(),'valueavalueb');
        
    }
    
    public function testNoDefaultsSetForGenerator()
    {
        
        $id = 'column_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $column = new Column($id,$parent,$event,$column_type,array('locale'=> 'china'));
        
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();     
        $column->addChild($child_a);        
            
        $column->merge();
        $this->assertFalse($column->hasOption('randomGenerator'));
        $this->assertFalse($column->hasOption('generatorSeed'));
        
    }
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Invalid configuration for path "config.generatorSeed": generatorSeed must be an integer
      */
    public function testNotIntergerRefusedForGeneratorSeed()
    {
        
        $id = 'column_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();

        
        $column = new Column($id,$parent,$event,$column_type,array('locale'=> 'china'));
        
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();     
        $column->addChild($child_a);        
            
       
        $column->setOption('generatorSeed','a non integer');
        
        $column->merge();
    }
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Invalid configuration for path "config.generatorSeed": generatorSeed must be an integer
      */
    public function testNullNotAcceptedGeneratorSeed()
    {
        $id = 'column_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $column = new Column($id,$parent,$event,$column_type,array('locale'=> 'china'));
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();     
        $column->addChild($child_a);   
        
        $column->setOption('generatorSeed',null);
        
        $column->merge();
    }
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Invalid configuration for path "config.generatorSeed": generatorSeed must be an integer
      */
    public function testEmptyStringNotAcceptedGeneratorSeed()
    {
        $id = 'column_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $column = new Column($id,$parent,$event,$column_type,array('locale'=> 'china'));
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();     
        $column->addChild($child_a);   

        
        $column->setOption('generatorSeed','');
        
        $column->merge();
        
        
    }
    
    
    public function testGeneratorOptionsAccepted()
    {
        
        $id = 'column_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $column = new Column($id,$parent,$event,$column_type,array('locale'=> 'china'));
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();     
        $column->addChild($child_a);   
        
        $column->setOption('generatorSeed',100);
        $column->setOption('randomGenerator','srand');
        $column->merge();
        
        $this->assertEquals(100,$column->getOption('generatorSeed'));
        $this->assertEquals('srand',$column->getOption('randomGenerator'));
        
    }
    
}
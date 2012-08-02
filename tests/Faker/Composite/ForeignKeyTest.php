<?php
namespace Faker\Tests\Faker\Composite;

use Faker\Components\Faker\Composite\ForeignKey,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Formatter\FormatEvents,
    Faker\Components\Faker\Formatter\GenerateEvent,
    Faker\Components\Faker\GeneratorCache,
    Faker\Components\Faker\CacheInterface,
    Doctrine\DBAL\Types\Type as ColumnType,
    Faker\Tests\Base\AbstractProject;
    
class ForeignKeyTest extends AbstractProject
{
    
    public function testImplementsCompositeInterface()
    {
        $id = 'table_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $column = new ForeignKey($id,$parent,$event);
        $column->setOption('name','foreign-key');
        
        $this->assertInstanceOf('Faker\Components\Faker\Composite\CompositeInterface',$column);
        $this->assertInstanceOf('Faker\Components\Faker\CacheInterface',$column);
    
    }
    
    public function testConfigurationParsed()
    {
        $id = 'table_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $foreign = new ForeignKey($id,$parent,$event,array('foreignTable'=>'tb1', 'foreignColumn' => 'clmn1','name' => 'foreign-key'));

        # assert use cache is default true
        $this->assertTrue($foreign->getUseCache()); 
        
        # setup cache (not testing validation)
        $cache = new GeneratorCache();
        $foreign->setGeneratorCache($cache);
        
        $foreign->merge();
        $foreign->validate();        
    }
    
    
    public function testToXml()
    {
        $id = 'tbl1.clmn1';
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $foreign = new ForeignKey($id,$parent,$event,array('foreignTable' => 'tbl1', 'foreignColumn' => 'clmn1','name' => 'foreign-key'));
        
        $xml = $foreign->toXml();
        $this->assertContains('<foreign-key name="tbl1.clmn1" foreignColumn="clmn1" foreignTable="tbl1">',$xml );
        $this->assertContains('</foreign-key>',$xml);
    
    }
    
    
    public function testProperties()
    {
        $id = 'tbl1.clmn1';
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
    
        $foreign = new ForeignKey($id,$parent,$event,array('name' => 'foreign-key'));
     
        $child_a = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $child_b = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
          
        $foreign->addChild($child_a);        
        $foreign->addChild($child_b);        
        
        $this->assertEquals($foreign->getChildren(),array($child_a,$child_b));
        $this->assertSame($foreign->getEventDispatcher(),$event);
        $this->assertEquals($parent,$foreign->getParent());
        $this->assertEquals($id,$foreign->getId());
    }
    
    
    public function testCacheInterface()
    {
        $id = 'table_1';
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $foreign = new ForeignKey($id,$parent,$event,array('foreignTable'=> 'china', 'foreignColumn' => 'abcd','name' => 'foreign-key'));
        
        
        # test the default use cache true
        $this->assertTrue($foreign->getUseCache());

        # setup cache (not testing validation)
        $cache = new GeneratorCache();
        $foreign->setGeneratorCache($cache);
        $this->assertSame($foreign->getGeneratorCache(),$cache);
                
        
    } 
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage Foreign-key requires a cache to be set
      */
    public function testValidateFailMissingCache()
    {
        $id = 'table_1';
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $foreign = new ForeignKey($id,$parent,$event,array('foreignTable'=> 'china', 'foreignColumn' => 'abcd','name' => 'foreign-key'));

        $this->assertTrue($foreign->getUseCache());
        
        $foreign->merge();
        $foreign->validate();
        
    }
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage The path "config.foreignTable" cannot contain an empty value, but got ""
      */
    public function testValidateFailEmptyForeignTable()
    {
        $id = 'table_1';
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $foreign = new ForeignKey($id,$parent,$event,array('foreignTable'=> '', 'foreignColumn' => 'aaa','name' => 'foreign-key'));

        $this->assertTrue($foreign->getUseCache());
        
        $foreign->merge();
        $foreign->validate();
        
    }
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage The path "config.foreignColumn" cannot contain an empty value, but got ""
      */
    public function testValidateFailEmptyForeignColumn()
    {
        $id = 'table_1';
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        
        $foreign = new ForeignKey($id,$parent,$event,array('foreignTable'=> 'aa', 'foreignColumn' => '','name' => 'foreign-key'));

        $this->assertTrue($foreign->getUseCache());
        
        $foreign->merge();
        $foreign->validate();
        
    }
    
    
    public function testValidatePassWithCache()
    {
         $id = 'table_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $foreign = new ForeignKey($id,$parent,$event,array('foreignTable'=> 'china', 'foreignColumn' => 'abcd','name' => 'foreign-key'));
        
        # test the use cache property
        $foreign->setUseCache(true);
        
        # setup cache (not testing validation)
        $cache = new GeneratorCache();
        $foreign->setGeneratorCache($cache);
        
        $foreign->merge();
        $foreign->validate();        
        
    }
    
    
    public function testCacheUsedInGenerate()
    {
        $id = 'table_1';
        $column_type = $this->getMockBuilder('Doctrine\DBAL\Types\Type')->disableOriginalConstructor()->getMock();
       
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')->getMock();
        $foreign = new ForeignKey($id,$parent,$event,array('foreignTable'=> 'china', 'foreignColumn' => 'abcd','name' => 'foreign-key'));
        $foreign->setUseCache(true);
        
        # setup the cache        
        $cache = new GeneratorCache();
        $cache->add('valueavalueb');
        $cache->add('valuecvalued');
        
        # set cache and validate        
        $foreign->setGeneratorCache($cache);
        $foreign->merge();
        $foreign->validate(); 
        
        # generate (fetch from cache)
        $valueA = $foreign->generate(1,array());
        $valueB = $foreign->generate(2,array());
        
        # assert
        $this->assertEquals('valueavalueb',$valueA);
        $cache->next();
        $this->assertEquals('valuecvalued',$valueB);
        
    }
    
}
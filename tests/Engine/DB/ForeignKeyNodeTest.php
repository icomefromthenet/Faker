<?php
namespace Faker\Tests\Engine\DB;

use Faker\Components\Engine\DB\Composite\ForeignKeyNode;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Tests\Base\AbstractProject;

/**
  *  Test the node implementes Generator and Visitor Interfaces
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ForeignKeyNodeTest extends AbstractProject
{
    
    public function testNewObject()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $cache     = $this->getMockBuilder('Faker\Components\Engine\Common\GeneratorCache')->disableOriginalConstructor()->getMock();
        
        $id        = 'foreignKeyNode';
            
        $type = new ForeignKeyNode($id,$event);
        $type->setResultCache($cache);
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\ForeignKeyNode',$type);
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\GeneratorInterface',$type);
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Composite\\VisitorInterface',$type);
        $this->assertEquals($cache,$type->getResultCache());
        
    }
    
    
    public function testUsesCache()
    {
        $id = 'foreignKeyNode';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $cache     = $this->getMockBuilder('Faker\Components\Engine\Common\GeneratorCache')->disableOriginalConstructor()->getMock();
              
        $cache->expects($this->once())
              ->method('rewind');
    
        $cache->expects($this->once())
              ->method('current')
              ->will($this->returnValue(5));
              
        $cache->expects($this->once())
              ->method('next');
              
        $foreignKey = new ForeignKeyNode($id,$event);
        $foreignKey->setResultCache($cache);
        $values = array();
        $this->assertEquals(5,$foreignKey->generate(1,$values));
        
        $foreignKey->setUseCache(false);
        $this->assertEquals(null,$foreignKey->generate(1,$values));
    }
    
    public function testNotRewindCacheRowNotEqualToFirst()
    {
        $id = 'foreignKeyNode';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $cache     = $this->getMockBuilder('Faker\Components\Engine\Common\GeneratorCache')->disableOriginalConstructor()->getMock();
              
        $cache->expects($this->exactly(0))
              ->method('rewind');
    
        $cache->expects($this->once())
              ->method('current')
              ->will($this->returnValue(5));
              
        $cache->expects($this->once())
              ->method('next');
              
        $foreignKey = new ForeignKeyNode($id,$event);
        $foreignKey->setResultCache($cache);
        $values = array();
        $this->assertEquals(5,$foreignKey->generate(2,$values));
        
        
    }
    
    
    
    public function testVisititorVisitsChildren()
    {
        $id = 'foreignKeyNode';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $foreignKey = new ForeignKeyNode($id,$event);
     
        $child_a = $this->getMockBuilder('Faker\Tests\Engine\DB\Mock\MockNode')
                        ->disableOriginalConstructor()
                        ->getMock();
                        
        $child_a->expects($this->once())
                ->method('acceptVisitor')
                ->with($this->isInstanceOf('Faker\Components\Engine\Common\Visitor\BasicVisitor'));
       
        $child_b = $this->getMockBuilder('Faker\Tests\Engine\DB\Mock\MockNode')
                            ->disableOriginalConstructor()
                            ->getMock();
                            
        $child_b->expects($this->once())
                ->method('acceptVisitor')
                ->with($this->isInstanceOf('Faker\Components\Engine\Common\Visitor\BasicVisitor'));
       
        
        $visitor = $this->getMockBuilder('Faker\Components\Engine\Common\Visitor\BasicVisitor')
                        ->disableOriginalConstructor()
                        ->getMock();
        
        $foreignKey->addChild($child_a);        
        $foreignKey->addChild($child_b); 
        
        $foreignKey->acceptVisitor($visitor);
        
    }
    
     /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage The child node "foreignTable" at path "config" must be configured
      */
    public function testValidationFailsNoForignTableSet()
    {
        $utilities = $this->createMock('Faker\Components\Engine\Common\Utilities'); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $id        = 'foreignKeyNode';
        $cache     = $this->getMockBuilder('Faker\Components\Engine\Common\GeneratorCache')->disableOriginalConstructor()->getMock();
         
        $type = new ForeignKeyNode($id,$event);
        $type->setResultCache($cache);
        
        $type->setOption('foreignColumn','aaaa');
        
        $type->validate();
        
    }
    
    
     /**
      *  @expectedException Faker\Components\Engine\Common\Composite\CompositeException
      *  @expectedExceptionMessage The child node "foreignColumn" at path "config" must be configured
      */
    public function testValidationFailsNoForignColumnSet()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $id        = 'foreignKeyNode';
        $cache     = $this->getMockBuilder('Faker\Components\Engine\Common\GeneratorCache')->disableOriginalConstructor()->getMock();
         
        $type = new ForeignKeyNode($id,$event);
        $type->setResultCache($cache);
        
        $type->setOption('foreignTable','aaaa');
        
        $type->validate();
        
    }
    
    
    public function testValidationPass()
    {
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');     
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $id        = 'foreignKeyNode';
        $cache     = $this->getMockBuilder('Faker\Components\Engine\Common\GeneratorCache')->disableOriginalConstructor()->getMock();
         
        $type = new ForeignKeyNode($id,$event);
        $type->setResultCache($cache);
        
        $type->setOption('foreignTable','aaaa');
        $type->setOption('foreignColumn','aaaa');
        $type->setOption('foreignColumn','aaaa');
        $type->validate();
        $this->assertTrue($type->getUseCache());
        
                    
        $type->setOption('silent',true);
        $type->validate();
        $this->assertFalse($type->getUseCache());
    }
    
}
/* End of File */
<?php
namespace Faker\Tests\Engine\Original\Type;

use Faker\Components\Engine\Original\Composite\CompositeInterface,
    Faker\Components\Engine\Original\Type\Type,
    Faker\Components\Engine\Original\TypeFactory,
    Faker\Tests\Base\AbstractProject;

class FactoryTest extends AbstractProject
{
    
    protected $original_extensnions;
    
    
    public function __construct()
    {
        $this->original_extensnions = TypeFactory::$types;
    }

    
    public function tearDown()
    {
        TypeFactory::clearExtensions();
        TypeFactory::registerExtensions($this->original_extensnions);
        parent::tearDown();
    }
    
    //  -------------------------------------------------------------------------
        
    public function testRegisterExtension()
    {
        $mock = $this->getMockBuilder('Faker\Components\Engine\Original\Type\Type')
                    ->setMockClassName('MockTypeTest')
                    ->disableOriginalConstructor()
                    ->getMockForAbstractClass();   

        TypeFactory::registerExtensions(array('mocka' => 'MockTypeTest'));  
    
        $this->assertTrue(true);
    }

    //  -------------------------------------------------------------------------
    
    public function testANewFactory() 
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Original\Utilities')
                        ->disableOriginalConstructor()
                        ->getMock();
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
                        
        $this->assertInstanceOf('Faker\Components\Engine\Original\TypeFactory',new TypeFactory($utilities,$event,$generator));        
    }
    
    //  -------------------------------------------------------------------------
    
    public function testCreateType()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Original\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        
        $mock = $this->getMockBuilder('Faker\Components\Engine\Original\Type\Type')
                    ->setMockClassName('MockTypeTestB')
                    ->disableOriginalConstructor()
                    ->getMockForAbstractClass();   

        TypeFactory::registerExtensions(array('mockb' => 'MockTypeTestB'));
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
    
        $typefactory = new TypeFactory($utilities,$event,$generator);
        
        $type = $typefactory->create('mockb',$parent);
        
        $this->assertInstanceOf('MockTypeTestB',$type);
    }
    
    //  -------------------------------------------------------------------------

    /**
      *  @expectedException Faker\Components\Engine\Original\Exception
      *  @expectedExceptionMessage Class not found at::MockTypeTestCa
      */
    public function testCreateTypeExceptionNotExist()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Original\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        
        $mock = $this->getMockBuilder('Faker\Components\Engine\Original\Type\Type')
                    ->setMockClassName('MockTypeTestC')
                    ->disableOriginalConstructor()
                    ->getMockForAbstractClass();   

        TypeFactory::registerExtensions(array('mockb' => 'MockTypeTestCa'));
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
    
        $typefactory = new TypeFactory($utilities,$event,$generator);
        
        $type = $typefactory->create('mockb',$parent);
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException Faker\Components\Engine\Original\Exception
      *  @expectedExceptionMessage Type not found at::mockda
      */
    public function testBadClassKeyException()
    {
        $id = 'schema_1';
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Original\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
                          
        $parent = $this->getMockBuilder('Faker\Components\Engine\Original\Composite\CompositeInterface')->getMock();
        
        $mock = $this->getMockBuilder('Faker\Components\Engine\Original\Type\Type')
                    ->setMockClassName('MockTypeTestD')
                    ->disableOriginalConstructor()
                    ->getMockForAbstractClass();   

        TypeFactory::registerExtensions(array('mockb' => 'MockTypeTestD'));
        
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
    
        $typefactory = new TypeFactory($utilities,$event,$generator);
        
        $type = $typefactory->create('mockda',$parent);
        
    }
    
    //  -------------------------------------------------------------------------
}
/* End of File */
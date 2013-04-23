<?php
namespace Faker\Tests\Engine\Common\Formatter;


use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Formatter\ValueConverter;

class ValueConverterTest extends AbstractProject
{

    public function testAddElement()
    {
        $element  = $this->getMockBuilder('Doctrine\DBAL\Types\Type')
                         ->setMethods(array('getSQLDeclaration','getName'))
                         ->disableOriginalConstructor()
                         ->getMock();
                         
        $key     = 'column1';

        $map     = new ValueConverter();
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection',$map);
        
        $map->set($key,$element);
        
        
        $this->assertTrue($map->containsKey($key));
        
    }
    
    
    public function testConvserionFunction()
    {
        $element  = $this->getMockBuilder('Doctrine\DBAL\Types\Type')
                         ->setMethods(array('getSQLDeclaration','getName','convertToDatabaseValue'))
                         ->disableOriginalConstructor()
                         ->getMock();
                         
        $platform = $this->getMockBuilder('Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->setMethods(array('getBooleanTypeDeclarationSQL',
                                            'getIntegerTypeDeclarationSQL',
                                            'getBigIntTypeDeclarationSQL',
                                            'getSmallIntTypeDeclarationSQL',
                                            '_getCommonIntegerTypeDeclarationSQL',
                                            'initializeDoctrineTypeMappings',
                                            'getClobTypeDeclarationSQL',
                                            'getBlobTypeDeclarationSQL',
                                            'getName'
                                            ))
                         ->disableOriginalConstructor()->getMock();
                         
        $key      = 'column1';
        $map      = new ValueConverter();
        $value    = 'a string value';
        
        $element->expects($this->once())
        ->method('convertToDatabaseValue')
        ->with($this->equalTo($value),$this->equalTo($platform))
        ->will($this->returnValue($value));
        
        $map->set($key,$element);
        
        $this->assertEquals($value,$map->convertValue($key,$platform,$value));
        
    }
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage Unknown column mapping at key::badkey
      */
    public function testBadKey()
    {
         $element  = $this->getMockBuilder('Doctrine\DBAL\Types\Type')
                         ->setMethods(array('getSQLDeclaration','getName','convertToDatabaseValue'))
                         ->disableOriginalConstructor()
                         ->getMock();
                         
        $platform = $this->getMockBuilder('Doctrine\DBAL\Platforms\AbstractPlatform')
                         ->setMethods(array('getBooleanTypeDeclarationSQL',
                                            'getIntegerTypeDeclarationSQL',
                                            'getBigIntTypeDeclarationSQL',
                                            'getSmallIntTypeDeclarationSQL',
                                            '_getCommonIntegerTypeDeclarationSQL',
                                            'initializeDoctrineTypeMappings',
                                            'getClobTypeDeclarationSQL',
                                            'getBlobTypeDeclarationSQL',
                                            'getName'
                                            ))
                         ->disableOriginalConstructor()->getMock();
                         
        $key      = 'column1';
        $map      = new ValueConverter();
        $value    = 'a string value';
        $map->set($key,$element);
        
        $map->convertValue('badkey',$platform,$value);
    }

    
    
}
/* End of File */
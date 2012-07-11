<?php
namespace Faker\Tests\Locale;

use Faker\Tests\Base\AbstractProject,
    Faker\Text\SimpleString,
    Faker\Locale\LocaleFactory,
    Faker\Locale\LocaleInterface;

class LocaleFactoryTest extends AbstractProject
{
    
    public function testExtensionInterfaceImplemented()
    {
        $string_factory = SimpleString::create('');
        $factory = new LocaleFactory($string_factory);
        
        $this->assertInstanceOf('Faker\ExtensionInterface',$factory);        
        
    }
    
    
    public function testRegisterExtension()
    {
        LocaleFactory::registerExtension('tmp','\Faker\Locale\Tmp');
        $this->assertTrue(LocaleFactory::hasExtension('tmp'));
        
    }
    
    public function testRegisterExtensions()
    {
        LocaleFactory::registerExtensions(array(
                                               'tmp'  => '\Faker\Locale\Tmp',
                                               'tmp2' => '\Faker\Locale\Tmp2'
                                               )
                                         );
        
        $this->assertTrue(LocaleFactory::hasExtension('tmp'));
        $this->assertTrue(LocaleFactory::hasExtension('tmp2'));
    }
    
    
    public function testLocaleCanBeLoaded()
    {
       $string_factory = SimpleString::create('');
       $factory = new LocaleFactory($string_factory); 
       $locale = $factory->create('en');
       
       $this->assertInstanceOf('Faker\Locale\LocaleInterface',$locale);
    }
    
    public function testLocalesLoadedAsFlyweight()
    {
       $string_factory = SimpleString::create('');
       $factory = new LocaleFactory($string_factory); 
       $localeA = $factory->create('en');
       $localeB = $factory->create('en');
        
       $this->assertSame($localeA,$localeB); 
        
    }
    
    /**
      *  @expectedException Faker\Exception
      *  @expectedExceptionMessage Unknown Locale at::enn
      */
    public function testLoadingLocaleNotRegistered()
    {
       $string_factory = SimpleString::create('');
       $factory = new LocaleFactory($string_factory); 
       $locale = $factory->create('enn');
       
       $this->assertInstanceOf('Faker\Locale\LocaleInterface',$locale);
        
    }
    
    /**
      *  @expectedException Faker\Exception
      *  @expectedExceptionMessage Unknown Locale at::tmp
      */
    public function testLoadingLocaleRegisteredNotExist()
    {
        LocaleFactory::registerExtension('tmp','\Faker\Locale\Tmp');
        $this->assertTrue(LocaleFactory::hasExtension('tmp'));
        
        $string_factory = SimpleString::create('');
        $factory = new LocaleFactory($string_factory); 
        $locale = $factory->create('tmp');
    }
    
    
}
/* End of File */
<?php
namespace Faker\Tests\Locale;

use Faker\Tests\Base\AbstractProject,
    Faker\Text\SimpleString,
    Faker\Locale\LocaleFactory,
    Faker\Locale\LocaleInterface;

class EnglishLocaleTest extends AbstractProject
{
    
    
   public function testCreateFromFactory()
   {
        $project = $this->getProject();
        $factory = $project->getLocaleFactory();
        $this->assertInstanceOf('Faker\Locale\LocaleFactory',$factory);
        
        $locale = $factory->create('en');
        
        $this->assertInstanceOf('Faker\Locale\LocaleInterface',$locale);
     
        return $locale;
   } 
    
   /**
     *  @depends  testCreateFromFactory
     */
   public function testGetLetters(LocaleInterface $en)
   {
        $alphabet = $en->getLetters();
        $this->assertInstanceOf('\Faker\Text\SimpleStringInterface',$alphabet);
        $this->assertEquals(26,$alphabet->length());
        
   }
   
   /**
     *  @depends  testCreateFromFactory
     */
   public function testFillerText(LocaleInterface $en)
   {
        $filler =$en->getFillerText();
        $this->assertNotEmpty($filler);
   }
   
   /**
     *  @depends  testCreateFromFactory
     */
   public function testHex(LocaleInterface $en)
   {
        $filler =$en->getHex();
        $this->assertNotEmpty($filler);
   }
   
   
   /**
     *  @depends  testCreateFromFactory
     */
   public function testConsonants(LocaleInterface $en)
   {
        $filler =$en->getConsonants();
        $this->assertNotEmpty($filler);
   }
   
   /**
     *  @depends  testCreateFromFactory
     */
   public function testVowels(LocaleInterface $en)
   {
        $filler =$en->getVowels();
        $this->assertNotEmpty($filler);
   }
   
}
/* End of File */
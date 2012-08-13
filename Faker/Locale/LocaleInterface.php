<?php
namespace Faker\Locale;

use Faker\Text\StringFactoryInterface;

/**
  * Locale Interface , all locals must implement this interface for
  * registration with the LocaleFactory
  *
  * @author Lewis Dyer <getintouch@icomefromthenet.com>
  * @since 1.0.3
  *  
  */
interface LocaleInterface
{
    
    /**
      *  Fetch the consonants from alphabet
      *
      *  @access public
      *  @return \Faker\Text\SimpleTextInterface
      */
    public function getConsonants();
    
    /**
      *  Fetch the vowels from alphabet
      *  
      *  @access public
      *  @return \Faker\Text\SimpleTextInterface
      */
    public function getVowels();    
    
    /**
      *  Fetch the letters of the alphabet
      *  
      *  @access public
      *  @return \Faker\Text\SimpleTextInterface
      */
    public function getLetters();
    
    /**
      *   Fetch an array of filler text
      *   
      *   @access public
      *   @return array \Faker\Text\SimpleTextInterface
      */
    public function getFillerText();
    
    /**
      *  Fetch Hexdecimal alphabet 
      * 
      *  @access public
      *  @return \Faker\Text\SimpleTextInterface
      */
    public function getHex();
    
    /**
      *  Class Constructor
      *
      *  @access public
      *  @param StringFactoryInterface $factory
      *  @return void
      */
    public function __construct(StringFactoryInterface $factory);
    
}
/* End of File */
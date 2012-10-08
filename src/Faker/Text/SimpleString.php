<?php
namespace Faker\Text;

/**
  *  This is a proxy for StringObject
  *
  *  This is a derivative work of the SimpleString project (link and licence below)
  *
  *  @since 1.0.3
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *   
  *  @link https://github.com/klaussilveira/SimpleString/blob/master/SimpleString.php
  *  @license http://www.opensource.org/licenses/bsd-license.php BSD License
  *  
  */
class SimpleString implements SimpleStringInterface , StringFactoryInterface
{
    /**
      *  @var SimpleStringInterface the method provider 
      */
    protected $provider;
    
    
    /**
      *  Class Constructor
      *
      *  @param SimpleStringInterface $provider
      */
    public function __construct(SimpleStringInterface $provider)
    {
        $this->provider = $provider;
    }
    
    
    
    //  ----------------------------------------------------------------------------
    # Methods Part of fluid interface
   
    /**
     * Inserts a string at the end of another string
     * 
     * @access public
     * @param string $string String to be appended
     * @return SimpleStringInterface
     */    
    public function append($string)
    {
        $this->provider->append($string);
        
        return $this;
    }
    
    /**
     * Inserts a string at the beginning of another string
     * 
     * @access public
     * @param string $string String to be prepended
     * @return SimpleStringInterface
     */
    public function prepend($string)
    {
        $this->provider->prepend($string);
        return $this;
    }
    
    
    /**
     * Removes the last character from a string
     * 
     * @access public
     * @return SimpleStringInterface
     */
    public function chop()
    {
        $this->provider->chop();
        return $this;
    }
    
    /**
      *
      *  Finds the last occurrence of a character in a string within another (strrchr)
      *
      *  @access public
      *  @param string $needle The string to find in haystack
      *  @param boolean $part Determines which portion of haystack this function returns. If set to TRUE, it returns all of haystack from the beginning to the last occurrence of needle. If set to FALSE, it returns all of haystack from the last occurrence of needle to the end
      *  @return SimpleStringInterface
      */
    public function cut($needle,$part)
    {
        $this->provider->cut($needle,$part);
        
        return $this;
    }
    
    
    /**
     * Shortens a string to a fixed limit
     * 
     * @access public
     * @param int $limit Limit of characters note not bytes
     * @return SimpleStringInterface
     */
    public function shorten($limit)
    {
        $this->provider->shorten($limit);
        
        return $this;
    }
    
    /**
     * Reverses a string
     * 
     * @access public
     * @return SimpleStringInterface
     */
    public function reverse()
    {
        $this->provider->reverse();
        
        return $this;
    }
    
    
    /**
     * Scrambles all words in a string
     * 
     * @access public
     * @return SimpleStringInterface
     */
    public function scramble()
    {
        $this->provider->scramble();
        
        return $this;
    }
    
    /**
     * Shuffles all characters in a string
     * 
     * @access public
     * @return SimpleStringInterface
     */
    public function shuffle()
    {
        $this->provider->shuffle();
        
        return $this;
    }
    
    
    /**
     * Cleans and optimizes the string to be search engine friendly (SEO)
     * 
     * @access public
     * @param string $separator Character that separates words
     * @return SimpleStringInterface
     */
    public function seo($separator = '-')
    {
        $this->provider->seo($separator);
        
        return $this;
    }
    
    
    /**
     * Emphasizes certain words or characters in a string using an HTML tag
     * 
     * @access public
     * @param string|array $targets Words or characters to be emphasized
     * @param string $rule HTML tag that will be used for emphasis
     * @return SimpleStringInterface
     */
    public function emphasize($targets, $rule)
    {
        $this->provider->emphasize($targets,$rule);
        
        return $this;
    }
    
    /**
     * Censors certain words or characters in a string and replaces them with a *
     * 
     * @access public
     * @param string|array $words Words or characters to be censored
     * @return SimpleStringInterface
     */
    public function censor($words)
    {
        $this->provider->censor($words);
        
        return $this;
    }
    
    /**
     * Converts the string to lowercase (e.g: lorem ipsum dolor)
     * 
     * @access public
     * @return SimpleStringInterface
     */
    public function toLowerCase()
    {
        $this->provider->toLowerCase();
        
        return $this;
    }
    
    /**
     * Converts the string to uppercase (e.g: LOREM IPSUM DOLOR)
     * 
     * @access public
     * @return SimpleStringInterface
     */
    public function toUpperCase()
    {
        $this->provider->toUpperCase();
        
        return $this;
    }
    
    /**
     * Converts the string to sentence case (e.g: Lorem ipsum dolor)
     * 
     * @access public
     * @return SimpleStringInterface
     */
    public function toSentenceCase()
    {
        $this->provider->toSentenceCase();
        
        return $this;
    }
    
    /**
     * Converts the string to title case (e.g: Lorem Ipsum Dolor)
     * 
     * @access public
     * @return SimpleStringInterface
     */
    public function toTitleCase()
    {
        $this->provider->toTitleCase();
        
        return $this;
    }
    
    /**
     * Converts the spaces in string to underscores and lowercases the string (e.g: lorem_ipsum_dolor)
     * 
     * @access public
     * @return SimpleStringInterface
     */
    public function toUnderscores()
    {
        $this->provider->toUnderscores();
        
        return $this;
    }
    
    /**
     * Converts the string to camel case (e.g: loremIpsumDolor)
     * 
     * @access public
     * @return SimpleStringInterface
     */
    public function toCamelCase()
    {
        $this->provider->toCamelCase();
        
        return $this;
    }
    
    /**
     * Removes all non-alpha characters in a string
     * 
     * @access public
     * @return SimpleStringInterface
     */
    public function removeNonAlpha()
    {
        $this->provider->removeNonAlpha();
        
        return $this;
    }
    
    /**
     * Removes all non-alphanumeric characters in a string
     * 
     * @access public
     * @return SimpleStringInterface
     */
    public function removeNonAlphanumeric()
    {
        $this->provider->removeNonAlphanumeric();
        
        return $this;
    }
    
    /**
     * Removes all non-numeric characters in a string
     * 
     * @access public
     * @return SimpleStringInterface
     */
    public function removeNonNumeric()
    {
        $this->provider->removeNonNumeric();
        
        return $this;
    }
    
    /**
     * Removes all duplicate words in a string
     * 
     * @access public
     * @return SimpleStringInterface
     */
    public function removeDuplicates()
    {
        $this->provider->removeDuplicates();
        
        return $this;
    }
    
    /**
     * Removes all delimiters in a string
     * 
     * @access public
     * @return SimpleStringInterface
     */
    public function removeDelimiters()
    {
        $this->provider->removeDelimiters();
        
        return $this;
    }
    
    /**
      *  Strip whitespace (or other characters) from the beginning and end of a string
      *
      *  @access public
      *  @return @return SimpleStringInterface
      */
    public function trim()
    {
        $this->provider->trim();
        
        return $this;
    }
    
    /**
      *  Strip whitespace (or other characters) from the end of a string
      *  
      *  @access public
      *  @return SimpleStringInterface
      */
    public function rtrim()
    {
        $this->provider->rtrim();
        
        return $this;
    }
    
    
    /**
      *  Convert the first letter to uppercase
      *
      *  @access public
      *  @return SimpleStringInterface
      */
    public function ucfirst()
    {
        $this->provider->ucfirst();
        
        return $this;
    }
    
    /**
      *  Convert the first letter to lowercase
      *
      *  @access public
      *  @return SimpleStringInterface
      */
    public function lcfirst()
    {
        $this->provider->lcfirst();
        
        return $this;
    }
    
    /**
      *  Repeat the current string
      * 
      *  @param integer $multiplier
      *  @return SimpleStringInterface
      */
    public function repeat($multiplier)
    {
        $this->provider->repeat($multiplier);
        
        return $this;
    }
    
    /**
      *  Set the case flag to true
      *
      *  @access public
      *  @return SimpleStringInterface
      */
    public function caseSensitive()
    {
        $this->provider->caseSensitive();
        
        return $this;
    }
    
    /**
      *  Set the case flag to false
      *
      *  @access public
      *  @return SimpleStringInterface;
      */
    public function caseInsensitive()
    {
        $this->provider->caseInsensitive();
        
        return $this;
    }
    
    /**
      *  Convert the internal encoding
      *
      *  @access public
      *  @return SimpleStringInterface
      */
    public function convertEncoding($to_encoding)
    {
        $this->provider->convertEncoding($to_encoding);
        
        return $this;
    }
    
    /**
      *  Clear object of its internal data
      *
      *  @return SimpleStringInterface
      *  @access public
      */
    public function clear()
    {
        $this->provider->clear();
        
        return $this;
    }
    
    
    /**
      *  Will run regex replace on current string
      *
      *  @param string $pattern
      *  @param string $replace
      *  @return SimpleStringInterface
      *  @access public
      */
    public function regexReplace($pattern,$replace)
    {
        $this->provider->regexReplace($pattern,$replace);
        
        return $this;
    }
    
    //  ----------------------------------------------------------------------------
    # Information Methods Not part of fluid interface
    
    
    /**
      *  Split the string using regex
      *
      *  @param string the split regex
      *  @return SimpleStringInterface
      */
    public function split($char)
    {
        return self::create('',$this->provider->split($char));
    }
    
    
    /**
     * Gives the intersection of two strings
     * 
     * @access public
     * @param string $words String to be intersected
     */
    public function intersect($words)
    {
        return self::create('',$this->provider->intersect($words));
    }
    
    /**
     * Returns the length of a string
     * 
     * @access public
     * @return int String length
     */
    public function length()
    {
        return $this->provider->length();
    }
    
    /**
     * Returns the number of words of a string
     * 
     * @access public
     * @return int Word count
     */
    public function words()
    {
        return self::create('',$this->provider->words()); 
    }
    
    /**
     * Checks if a string contains another one
     * 
     * @access public
     * @param string $string String to be checked
     * @return boolean False if it does not contain, true if it does
     */
    public function contains($string , $offset = 0)
    {
        return $this->provider->contains($string,$offset);
    }
    
    /**
      *  Find position of first occurrence of string in a string
      *  Alias to strpos operations
      *
      *  @access public
      *  @param $string The string to find in haystack.
      *  @param integer $offset The search offset. If it is not specified, 0 is used.
      */
    public function firstPosition($needle,$offset = 0)
    {
        return $this->provider->firstPosition($needle,$offset);
    }
    
    /**
      *  Find position of last occurrence of a string in a string
      *  Alias to strpos operations
      *
      *  @param $string The string to find in haystack.
      *  @param integer $offset begin searching an arbitrary number of characters in. Negative values will stop searching at an arbitrary point prior to the end of the string.
      */
    public function lastPosition($needle,$offset)
    {
        return $this->provider->lastPosition($needle,$offset);
    }
    
    
    /**
     * Returns our manipulated string when the object is echoed
     * 
     * @access public
     * @return string Manipulated string
     */
    public function __toString()
    {
        return $this->provider->__toString();
    }
    
    //  ----------------------------------------------------------------------------
    # Retuns new SimpleString objects (no disructive).
    
    /**
      *  Return a substring as a new SimpleString object
      *  not modify the current string.
      *
      *  @access public
      *  @param integer $start Position of first character to use from str.
      *  @param integer $length Maximum number of characters to use from str.
      *  @return SimpleStringInterface
      */
    public function sub($start , $length = null)
    {
        return self::create('',$this->provider->sub($start,$length));
    }
    
    
    /**
      *  Will fetch a substring as a new SimpleString
      *  not modify the current string
      *
      * @access public
      * @param integer Starting position in bytes.
      * @param Length in bytes.
      * @return SimpleStringInterface
      */
    public function bytes($start,$length = null)
    {
        return self::create('',$this->provider->bytes($start,$length));
    }
    
    
     /**
      *  Fetch a charater at postion x
      *
      *  @access public
      *  @param integer postion
      *  @return string the character at postion x
      */
    public function charAt($pos)
    {
        return $this->provider->charAt($pos);
    }
    
    
    //  ----------------------------------------------------------------------------
    # StringFactoryInterface
    
    /**
      *  Create new instance of the SimpleStringInterface 
      *
      *  @return SimpleStringInterface
      *  @param string $string a inital string
      *  @param SimpleStringInterface $provider the provider to use default to null
      *  @param string $encoding defaults to UTF-8
      */
    public static function create($string, SimpleStringInterface $provider = null ,$encoding = 'UTF-8')
    {
        if($provider === null) {
            $provider = new MbProvider($string,$encoding);
        }
        
        return new self($provider);
    }
    
    //  ----------------------------------------------------------------------------
}
/* End of File */
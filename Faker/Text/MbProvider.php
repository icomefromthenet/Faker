<?php
namespace Faker\Text;

/**
  *  Provies string functions include MB_Extension
  *
  *  This is a derivative work of the SimpleString project (link and licence below)
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.3
  *  @link https://github.com/klaussilveira/SimpleString/blob/master/SimpleString.php
  *  @license http://www.opensource.org/licenses/bsd-license.php BSD License
  */
class MbProvider implements SimpleStringInterface
{
    /**
      *  @var the current character encoding 
      */
    protected $encoding;
    
    /**
      *  @var string the current string 
      */
    protected $str;
    
    /**
      *  @var case flag 
      */
    protected $case;
   
    /**
     *  @var string the previous encoding format 
     */
    protected $previous_encoding;
   
    /**
      *  @var integer the number of times format been changed  
      */
    protected $encoding_change_count;
   
    /**
      *  Class constructor
      *
      *  @param string $string the starting string
      *  @param string $encoding the encoding format to use
      *  @access public
      *  @return void
      */
    public function __construct($string,$encoding = 'UTF-8')
    {
        $this->encoding_change_count = 0;
        $this->encoding              = $encoding;
        $this->str                   = $string;
        $this->case                  = true; #defaults case sensitive
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
        $this->str .= $string;
        
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
        $this->str = $string . $this->str;
        
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
        $this->str = mb_substr($this->str, 0, -1,$this->encoding);
        
        return $this;
    }
    
    /**
      *  Finds the last occurrence of a character in a string within another (strrchr)
      *  
      *  @param string  $needle The string to find in haystack
      *  @param boolean $part   Determines which portion of haystack this function returns. If set to TRUE, it returns all of haystack from the beginning to the last occurrence of needle. If set to FALSE, it returns all of haystack from the last occurrence of needle to the end
      *  @return SimpleStringInterface
      */
    public function cut($needle,$part = false)
    {
        if(is_bool($this->case) === false) {
            throw new \InvalidArgumentException('MbProvider::cut::$case must be a boolean');
        }
        
        $function = ($this->case === true) ? 'mb_strstr' : 'mb_strrichr';
        $result = $function($this->str, $needle, $part, $this->encoding);

        if($result === false) {
            throw new \RuntimeException("MbProvider::cut::$needle was not found");
        }
        
        $this->str = $result;
        
        return $this;
    }
    
    
    /**
     * Shortens a string to a fixed limit
     * 
     * @access public
     * @param integer  $limit Limit of characters note not bytes
     * @return SimpleStringInterface
     */
    public function shorten($limit)
    {
        if (mb_strlen($this->str,$this->encoding) >= $limit) {
            $this->str = mb_substr($this->str, 0, $limit, $this->encoding);
        }
        
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
        $this->str = self::mb_strrev($this->str,$this->encoding);
        
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
        $string = mb_split("\s",$this->str);
        shuffle($string);
        $this->str = implode(' ', $string);
        
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
        $this->str = self::mb_shuffle($this->str,$this->encoding);
        
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
        $accents = array('Š' => 'S',
                         'š' => 's',
                         'Ð' => 'Dj',
                         'Ž' => 'Z',
                         'ž' => 'z',
                         'À' => 'A',
                         'Á' => 'A',
                         'Â' => 'A',
                         'Ã' => 'A',
                         'Ä' => 'A',
                         'Å' => 'A',
                         'Æ' => 'A',
                         'Ç' => 'C',
                         'È' => 'E',
                         'É' => 'E',
                         'Ê' => 'E',
                         'Ë' => 'E',
                         'Ì' => 'I',
                         'Í' => 'I',
                         'Î' => 'I',
                         'Ï' => 'I',
                         'Ñ' => 'N',
                         'Ò' => 'O',
                         'Ó' => 'O',
                         'Ô' => 'O',
                         'Õ' => 'O',
                         'Ö' => 'O',
                         'Ø' => 'O',
                         'Ù' => 'U',
                         'Ú' => 'U',
                         'Û' => 'U',
                         'Ü' => 'U',
                         'Ý' => 'Y',
                         'Þ' => 'B',
                         'ß' => 'Ss',
                         'à' => 'a',
                         'á' => 'a',
                         'â' => 'a',
                         'ã' => 'a',
                         'ä' => 'a',
                         'å' => 'a',
                         'æ' => 'a',
                         'ç' => 'c',
                         'è' => 'e',
                         'é' => 'e',
                         'ê' => 'e',
                         'ë' => 'e',
                         'ì' => 'i',
                         'í' => 'i',
                         'î' => 'i',
                         'ï' => 'i',
                         'ð' => 'o',
                         'ñ' => 'n',
                         'ò' => 'o',
                         'ó' => 'o',
                         'ô' => 'o',
                         'õ' => 'o',
                         'ö' => 'o',
                         'ø' => 'o',
                         'ù' => 'u',
                         'ú' => 'u',
                         'û' => 'u',
                         'ý' => 'y',
                         'ý' => 'y',
                         'þ' => 'b',
                         'ÿ' => 'y',
                         'ƒ' => 'f');
        
        # replace accented characters
        
        foreach($accents as $accent => $normal) {
            $this->replace($accent,$normal);
        }
        
        $this->toLowerCase()
             ->regexReplace('[^a-zA-Z0-9\s]', '')
             ->trim()
             ->caseInsensitive()
             ->replace(' ',$separator);
        
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
        if (is_array($targets)) {
            foreach ($targets as $target) {
                $this->str = str_replace($target, "<{$rule}>{$target}</{$rule}>", $this->str);
            }
        } else {
                $this->str = str_replace($targets, "<{$rule}>{$targets}</{$rule}>", $this->str);    
        }
        
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
        if (is_array($words)) {
            foreach ($words as $word) {
                $censor = '';
                
                foreach (self::mb_split($word,$this->encoding) as $letter) {
                    $censor .= '*';
                }
                
                $this->str = str_replace($word, $censor, $this->str);
            }
        } else {
            $censor = '';
            
            foreach (self::mb_split($words,$this->encoding) as $letter) {
                $censor .= '*';
            }
            
            $this->str = str_replace($words, $censor, $this->str);    
        }
     
        
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
        $this->str = mb_strtolower($this->str,$this->encoding);
        
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
        $this->str = mb_strtoupper($this->str,$this->encoding);
        
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
        $this->str = (string) $this->trim()
                                    ->toLowerCase()
                                    ->ucfrist();
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
        $this->str = mb_convert_case($this->str,MB_CASE_TITLE,$this->encoding);
        
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
        $this->toLowerCase()->replace(' ', '_');
        
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
        $this->trim()->toTitleCase()->replace(' ', '')->lcfirst();
        
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
        $this->regexReplace('[^a-zA-Z\s]','');
        
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
        $this->regexReplace('[^a-zA-Z0-9\s]','');
        
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
        $this->regexReplace('[^0-9\s]','');
        
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
        $breakdown[0] = self::mb_strtok(' ',$this->str,$this->encoding);
        
        #init the token parser
        if($breakdown[0] !== false) {
            while(($word = self::mb_strtok(' ',null,$this->encoding)) !== false) {
                $breakdown[] = $word;
            }

            $this->str = implode(' ', array_unique($breakdown));    
        }
        
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
        $this->replace(array(
            ' ',
            '-',
            ',',
            '.',
            '?',
            '!',
        ), '');
        
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
        $this->overrideEncoding();
        
        // \p{Z} # unicode spaces
        // \p{C} # unicode catch others
        
        # eregi is case insensitive version of ereg
        $this->str =  preg_replace('/(^[\pZ|\pC]+)/u', '', $this->str);
            
        //removes chr(0xc2) . chr(0xa0) . '#page#' . chr(0xc2) . chr(0xa0);    
            
        $this->resetEncoding();    
            
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
        
        $this->overrideEncoding();
        
        // \p{Z} # unicode spaces
        // \p{C} # unicode catch others
        
        # eregi is case insensitive version of ereg
        $this->str =  preg_replace('/([\pZ|\pC]+$)/u', '', $this->str);

        $this->resetEncoding();
        
        return $this;
    }
    
     /**
      *  Convert the first letter to uppercase
      *
      *  @access public
      *  @return SimpleStringInterface
      */
    public function ucfrist()
    {
        $this->str = (string) $this->sub(0,1)->toUpperCase()->append($this->sub(1,$this->length()));
                            
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
        $this->str = (string) $this->sub(0,1)->toLowerCase()->append($this->sub(1,$this->length()));
                            
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
        if(is_init($multiplier) === false && (integer) $multiplier > 0) {
            throw \InvalidArgumentException('MbProvider::repeat::$multiplier must be an integer > 0');
        }
        
        $str = clone $this->str;      
        for($i = 0; $i <= $max; $i++) {
            $this->append($str);
        }
        unset($str);
        
        return $this;
    }
    
    /**
      *  Replace all occurrences of the search string with the replacement string
      *
      * @param boolean $case insensetive
      * @param string  $search
      * @param string  $replace
      * @return SimpleStringInterface
      * @access public
      *  
      */
    public function replace($search,$replace,$count = null)
    {
        if($this->case === false) {
            $function = 'str_ireplace';
        }
        else {
            $function = 'str_replace';
        }
        
        if($count !== null) {
            $this->str = $function($search,$replace, $this->str, $count);
        }
        else {
            $this->str = $function($search,$replace, $this->str);
        }
            
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
        $this->overrideEncoding();
        
        if($this->case === false) {
            if(($result = mb_eregi_replace($pattern,$replace,$this->str)) !== false) {
                $this->str = $result;
            }
                
        } else {
            if(($result = mb_ereg_replace($pattern,$replace,$this->str)) !== false) {
                $this->str = $result;
            } 
        }
        
        $this->resetEncoding();
        
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
        $this->case = true;
        
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
        $this->case = false;
        
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
    public function split($char,$limit = null)
    {
        $this->overrideEncoding();
     
        if($limit === null) {
            $split = mb_split($char,$this->str);
        } else {
            $split = mb_split($char,$this->str,$limit);
        }
        
        $this->resetEncoding();
        
        return $split;
    }
    
    /**
     * Returns the length of a string
     * 
     * @access public
     * @return int String length
     */
    public function length()
    {
        return mb_strlen($this->str,$this->encoding);
    }
    
    /**
     * Returns the number of words of a string
     * 
     * @access public
     * @return int Word count
     */
    public function words()
    {
        throw new \InvalidArgumentException('MbProvider::words Not Implemented');
    }
    
    /**
     * Checks if a string contains another one
     * 
     * @access public
     * @param string $string String to be checked
     * @return boolean False if it does not contain, true if it does
     */
    public function contains($string)
    {
        return (mb_stripos($this->str, $this->encoding) === false) ? false : true;
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
        $function = 'mb_strpos';
        
        if(is_bool($this->case) === false) {
            throw new \InvalidArgumentException('MbProvider::firstPosition::case must be a boolean');
        }
        
        if($this->case === false) {
            $function = 'mb_stripos';
        }
        
        return $function($this->str,$needle,$offset,$this->encoding);
    }
    
    /**
      *  Find position of last occurrence of a string in a string
      *  Alias to strpos operations
      *
      *  @param $string The string to find in haystack.
      *  @param integer $offset begin searching an arbitrary number of characters in. Negative values will stop searching at an arbitrary point prior to the end of the string.
      */
    public function lastPosition($needle,$offset = 0)
    {
         $function = 'mb_strrpos';
        
        if(is_bool($this->case) === false) {
            throw new \InvalidArgumentException('MbProvider::firstPosition::case must be a boolean');
        }
        
        if($this->case === false) {
            $function = 'mb_strripos';
        }
        
        return $function($this->str,$needle,$offset,$this->encoding);
    }
    
    
    /**
     * Returns our manipulated string when the object is echoed
     * 
     * @access public
     * @return string Manipulated string
     */
    public function __toString()
    {
        return $this->str;
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
        return new self(mb_substr($this->str,$start,$length,$this->encoding),$this->encoding);
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
        return new self(mb_strcut($this->str,$start,$length,$this->encoding),$this->encoding);
    }
    
    
     /**
     * Gives the intersection of two strings
     * 
     * @access public
     * @param string $words String to be intersected
     * @return 
     */
    public function intersect($words)
    {
        $string = explode(' ', $this->str);
        $words  = explode(' ', $words);
        $intersection = array_intersect($string, $words);
        $str = implode(' ', $intersection);
        
        return new self($str,$this->encoding);
    }
    
    
     /**
      *  Clear object of its internal data
      *
      *  @return SimpleStringInterface
      *  @access public
      */
    public function clear()
    {
        $this->str = '';
        
        return $this;
    }
    
    //  ----------------------------------------------------------------------------
    # Mb Extensions
    
    /**
      *  Reverse a multi-byte string
      *
      *  @link http://stackoverflow.com/questions/434250/how-to-reverse-a-unicode-string
      *  @author unknown
      *  @licence unknown
      *
      *  @param string $text 
      *  @param string $encoding 
      */    
    public static function mb_strrev($text, $encoding = null)
    {
        $funcParams = array($text);
        if ($encoding !== null) {
            $funcParams[] = $encoding;
        }
        
        $length = call_user_func_array('mb_strlen', $funcParams);
        $output = '';
        $funcParams = array($text, $length, 1);
        
        if ($encoding !== null) {
            $funcParams[] = $encoding;
        }
        
        while ($funcParams[1]--) {
             $output .= call_user_func_array('mb_substr', $funcParams);
        }
        
        return $output;
    }
    
    /**
      *  Will shuffle a unicode string
      *
      *  @access public
      *  @return string
      *  @param
      */
    public static function mb_shuffle($text,$encoding)
    {
        $stop   = mb_strlen($text,$encoding); 
        $result = array(); 

        for( $idx = 0; $idx < $stop; $idx++)  { 
            $result[] = mb_substr($text, $idx, 1,$encoding); 
        }
        
        shuffle($result);
        return join("", $result);
    }
    
    /**
      *  Split a text block into an array
      *
      *  @param string $text
      *  @param string $encoding
      *  @return string[]
      *  @access public
      */
    public static function mb_split($text,$encoding)
    {
        $array = array();
        $strlen = mb_strlen($text,$encoding); 
        
        while ($strlen--) { 
            $array[] = mb_substr($text, $strlen, 1, $encoding); 
        }
        
        return array_reverse($array); 
    }
    
   
   /**
     *  Token parse a string
     *
     *  @param string $delimiters a string deliminator
     *  @param string $str the string to parse
     */ 
   public static function mb_strtok($delimiter, $str = null, $encoding)
   {
       static $pos = 0; // Keep track of the position on the string for each subsequent call.
       static $string = "";
    
       # If a new string is passed, reset the static parameters.
       if(!empty($str)) {
           $pos = 0;
           $string = $str;
       }
       
       # Initialize the token.
       $token = "";
       while ($pos < mb_strlen($string,$encoding)) {
           
           $char = mb_substr($string, $pos, 1,$encoding);
           $pos++;
    
           if(mb_strpos($delimiter, $char,0,$encoding) === false) {
               $token .= $char;
           }
           else {
               break;
           }
    
       }
    
       # Check whether there is a last token to return.
       return (empty($token)) ? false : $token;
       
    }
    
    //  ----------------------------------------------------------------------------
    # Encoding Call Stack
    
    /**
      *  Override the global encoding 
      *  will only change if call stack at 0
      *  use the value passed in constructor
      *
      *  @access public
      *  @return SimpleStringInterface
      *  
      */
    public function overrideEncoding()
    {
        if($this->encoding_change_count <= 0) {
            $this->previous_encoding = mb_internal_encoding();
            mb_internal_encoding($this->encoding);
            
            # set explicity as resetEncoding() could have been called
            # before the first call to overrideEncoding()
            $this->encoding_change_count = 1; 
        }
        else {
            $this->encoding_change_count += 1;    
        }
        
        
        return $this;
        
    }
    
    /**
      *  Reset the global encoding to starting value
      *  if they are not identical and call stack === 1
      *  only work if self::overrideEncoding() has been called once
      *
      *  @access public
      *  @return SimpleStringInterface
      */
    public function resetEncoding()
    {
        if($this->encoding_change_count === 1) {
            mb_internal_encoding($this->previous_encoding);
            $this->previous_encoding = null;
            $this->encoding_change_count = 0;
        }
        
        $this->encoding_change_count -= 1;
            
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
        $this->str = mb_convert_encoding($this->str,$to_encoding,$this->encoding);
        $this->encoding = $to_encoding;
        $this->encoding_change_count = 0;
        
        return $this;
    }
    
    
    
}
/* End of File */
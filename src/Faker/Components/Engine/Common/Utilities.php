<?php
namespace Faker\Components\Engine\Common;

use Faker\Locale\LocaleInterface;
use Faker\Text\SimpleString;
use Faker\Text\StringIterator;
use PHPStats\Generator\GeneratorInterface;

/**
  *  This class contains some common methods used to generate
  *  data.
  *
  * @author Lewis Dyer <getintouch@icomefromthenet.com>
  * @since 1.0.4
  * 
  */
class Utilities
{

    //  -------------------------------------------------------------------------

    /**
     * Converts all x's and X's in a string with a random digit. X's: 1-9, x's: 0-9.
     *
     * @param string str the DSL
     * @param GeneratorInterface $random
     */
    public function generateRandomNum($str,GeneratorInterface $random)
    {
        // loop through each character and convert all unescaped X's to 1-9 and
        // unescaped x's to 0-9.
        $new_str  = SimpleString::create("");
        $str      = SimpleString::create($str);
        $iterator = new StringIterator($str);
        
        foreach ($iterator as $index => $char) {
            
            # check if character is escaped
            if ($char == '\\' && ($iterator->peek() == "X" || $iterator->peek() == "x")) {
                $iterator->next();
                continue;
            } else if ($char === "X") {
                $new_str->append((string) round($random->generate(1, 9)));
            } else if ($char === "x") {
                $new_str->append((string) round($random->generate(0, 9)));
            } else {
                $new_str->append((string) $char);
            }    
        }
        
        # trim remove white space
        $new_str->trim();
        
        return (string) $new_str;
    }

    //  -------------------------------------------------------------------------
    
    /**
     * Converts the following characters in the string and returns it:
     *
     *     C, c, E - any consonant (Upper case, lower case, any)
     *     V, v, F - any vowel (Upper case, lower case, any)
     *     L, l, V - any letter (Upper case, lower case, any)
     *     X       - 1-9
     *     x       - 0-9
     *     H       - 0-F
     *
     *  @param string $str the DSL
     *  @param GeneratorInterface $random
     */
    public function generateRandomAlphanumeric($str,GeneratorInterface $random, LocaleInterface $locale)
    {
        $letters    = $locale->getLetters();
        $consonants = $locale->getConsonants();
        $vowels     = $locale->getVowels();
        $hex        = $locale->getHex();
        
        // loop through each character and convert all unescaped X's to 1-9 and
        // unescaped x's to 0-9.
        $new_str = SimpleString::create("");
        $str     = SimpleString::create($str);                
        for ($i = 0; $i < $str->length(); $i++) {
            
            switch ($str->charAt($i)) {
                // Numbers
                case "X":
                    $new_str->append(round($random->generate(1, 9)));
                break;
                case "x":
                    $new_str->append(round($random->generate(0, 9)));
                break;
                
                // Hex
                case "H":
                    $new_str->append($hex->charAt(round($random->generate(0, $hex->length()) - 1)));
                break;
                    
                // Letters
                case "L":
                    $new_str->append($letters->charAt(round($random->generate(0, $letters->length()) - 1)));
                break;
                case "l":
                    $new_str->append(\mb_strtolower($letters->charAt(round($random->generate(0, $letters->length()) - 1))));
                break;
                case "D":
                    $bool = round($random->generate(0,1));
                    if ($bool === 0)
                        $new_str->append($letters->charAt(round($random->generate(0, $letters->length()) - 1)));
                    else
                        $new_str->append(\mb_strtolower($letters->charAt(round($random->generate(0, $letters->length()) - 1))));
                    break;

                // Consonants
                case "C":
                    $new_str->append($consonants->charAt(round($random->generate(0, $consonants->length()) - 1)));
                break;
                case "c":
                    $new_str->append(\mb_strtolower($consonants->charAt(round($random->generate(0,$consonants->length()) - 1))));
                break;
                case "E":
                    $bool = round($random->generate(0,1));
                    if ($bool === 0)
                        $new_str->append($consonants->charAt(round($random->generate(0, $consonants->length()) - 1)));
                    else
                        $new_str->append(\mb_strtolower($consonants->charAt(round($random->generate(0, $consonants->length()) - 1))));
                    break;

                // Vowels
                case "V":
                    $new_str->append($vowels->charAt(round($random->generate(0,$vowels->length()) - 1)));
                break;
                case "v":
                    $new_str->append(\mb_strtolower($vowels->charAt(round($random->generate(0,$vowels->length()) - 1))));
                break;
                case "F":
                    $bool = round($random->generate(0,1));
                    if ($bool === 0)
                        $new_str->append($vowels->charAt(round($random->generate(0,$vowels->length()) - 1)));
                    else
                        $new_str->append(\mb_strtolower( $vowels->charAt(round($random->generate(0,$vowels->length()) - 1))));
                break;
     
                //space char
                case "S":
                case "s":
                    $new_str->append(" ");
                break;    
                default:
                    $new_str->append($str->charAt($i));
                break;
            }
        }

        return (string) $new_str;
    }

    
    //  -------------------------------------------------------------------------
    
    /**
     * Returns a random subset of an array. The result may be empty, or the same set.
     *
     * @param array $set - the set of items
     * @param integer $num - the number of items in the set to return
     */
    public function returnRandomSubset($set, $num)
    {
        // check $num is no greater than the total set
        if ($num > \count($set)) {
            $num = \count($set);
        }
        
        \shuffle($set); 
        
        return \array_slice($set, 0, $num);
    }

    
    //  -------------------------------------------------------------------------
  
    /**
     * Sorts a multidimensional (2 deep) array based on a particular key.
     *
     * @param array $array
     * @param mixed $key
     * @return array
     */
    public function arraySort($array, $key)
    {
        $sort_values = array();
        
        for ($i = 0; $i < \sizeof($array); $i++) {
            $sort_values[$i] = $array[$i][$key];
        }
        
        \asort($sort_values);
        \reset($sort_values);
        
        while (list ($arr_key, $arr_val) = \each($sort_values)) {
            $sorted_arr[] = $array[$arr_key];
        }
        
        return $sorted_arr;
    }

   
   //  -------------------------------------------------------------------------
   
    /**
     * This function is like rand
     *
     * @param array $weights
     * @param GeneratorInterface $random
     * @return float
     */
    public function getWeightedRand($weights,GeneratorInterface $random)
    {
        $r = $random->generate(1, 1000);
        $offset = 0;
        foreach ($weights as $k => $w) {
            
            $offset += $w * 1000;
            
            if ($r <= $offset) {
                return $k;
            }
        }
    }
    
}
/* End of File */
<?php
namespace Faker\Components\Faker;

use Faker\Project,
    Faker\Locale\LocaleInterface,
    Faker\Text\SimpleString,
    Faker\Generator\GeneratorInterface;

/**
  *  This class contains some common methods used to generate
  *  data.
  *
  *  Also provides all dependecies to the DataTypes hiding the
  *  implementation of the global di class, this is needed to
  *  prevent developers DataType extensions from breaking due
  *  changes in the format of the Dependency Injector.
  */
class Utilities
{
    /**
      *  @var Faker\Project the global dependency injector 
      */
    protected $di;
   
    
   /**
     *  Class Constructor
     *
     *  @var Faker/Project $di
     */
    public function __construct(Project $di)
    {
        $this->di = $di;
    }
   
   

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
        $new_str = "";
        for ($i = 0; $i < \strlen($str); $i++) {
            if ($str[$i] == '\\' && ($str[$i + 1] == "X" || $str[$i + 1] == "x")) {
                continue;
            } else if ($str[$i] == "X") {
                if ($i != 0 && ($str[$i - 1] == '\\')) {
                    $new_str .= "X";
                } else {
                    $new_str .= $random->generate(1, 9);
                }
            } else if ($str[$i] == "x") {
                if ($i != 0 && ($str[$i - 1] == '\\')) {
                    $new_str .= "x";
                } else {
                    $new_str .= $random->generate(0, 9);
                }
            }else
                $new_str .= $str[$i];
        }

        return \trim($new_str);
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
                
        for ($i = 0; $i < \strlen($str); $i++) {
            
            switch ($str[$i]) {
                // Numbers
                case "X":
                    $new_str .= ceil($random->generate(1, 9));
                break;
                case "x":
                    $new_str .= ceil($random->generate(0, 9));
                break;
                
                // Hex
                case "H":
                    $new_str .= $hex->charAt(ceil($random->generate(0, $hex->length()) - 1));
                break;
                    
                // Letters
                case "L":
                    $new_str .= $letters->charAt(ceil($random->generate(0, $letters->length()) - 1));
                break;
                case "l":
                    $new_str .= \strtolower($letters[ceil($random->generate(0, $letters->length()) - 1)]);
                break;
                case "D":
                    $bool = ceil($random->generate(0,1));
                    if ($bool === 0)
                        $new_str .= $letters[ceil($random->generate(0, $letters->length()) - 1)];
                    else
                        $new_str .= \strtolower($letters[ceil($random->generate(0, $letters->length()) - 1)]);
                    break;

                // Consonants
                case "C":
                    $new_str .= $consonants[ceil($random->generate(0, $consonants->length()) - 1)];
                break;
                case "c":
                    $new_str .= \strtolower($consonants[ceil($random->generate(0,$consonants->length()) - 1)]);
                break;
                case "E":
                    $bool = ceil($random->generate(0,1));
                    if ($bool === 0)
                        $new_str .= $consonants[ceil($random->generate(0, $consonants->length()) - 1)];
                    else
                        $new_str .= \strtolower($consonants[ceil($random->generate(0, $consonants->length()) - 1)]);
                    break;

                // Vowels
                case "V":
                    $new_str .= $vowels[ceil($random->generate(0,$vowels->length()) - 1)];
                break;
                case "v":
                    $new_str .= \strtolower($vowels[ceil($random->generate(0,$vowels->length()) - 1)]);
                break;
                case "F":
                    $bool = ceil($random->generate(0,1));
                    if ($bool === 0)
                        $new_str .= $vowels[ceil($random->generate(0,$vowels->length()) - 1)];
                    else
                        $new_str .= \strtolower($vowels[ceil($random->generate(0,$vowels->length()) - 1)]);
                break;
     
                //space char
                case "S":
                case "s":
                    $new_str .= " ";
                break;    
                default:
                    $new_str .= $str[$i];
                break;
            }
        }

        return $new_str;
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
    
    
    //  -------------------------------------------------------------------------
    # Dependencies
    
    /**
      * Fetch a faker database
      *
      * @access public
      * @return \Doctine\DBAL\Connection
      */
    public function getGeneratorDatabase()
    {
        return $this->di['faker_database'];
    }
    
    /**
      *  Fetch the template manager
      *
      *  @access public
      *  @return \Faker\Components\Templating\Manager
      */
    public function getTemplatingManager()
    {
        return $this->di['template_manager'];        
    }
    
    /**
      *  Fetch the Writer manager
      *
      *  @access public
      *  @return \Faker\Components\Writer\Manager 
      */
    public function getWriterManager()
    {
        return $this->di['writer_manager'];
    }
    
    /**
      *  Fetch the Faker manager
      *
      *  @access public
      *  @return \Faker\Components\Faker\Manager
      */
    public function getFakerManager()
    {
        return $this->di['faker_manager'];
    }
    
       
    /**
      *  Fetch the Config manager
      *
      *  @access public
      *  @return \Faker\Components\Config\Manager
      */
    public function getConfigManager()
    {
        return $this->di['config_manager'];
    }

    /**
      *  Fetch the Source IO
      *
      *  @access public
      *  @return \Faker\Io\Io
      */    
    public function getSourceIo()
    {
        return $this->di['source_io'];
    }
    
}
/* End of File */
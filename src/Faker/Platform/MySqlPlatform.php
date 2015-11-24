<?php
namespace Faker\Platform;

use Doctrine\DBAL\Platforms\MySqlPlatform as DoctrineMySqlPlatform;

class MySqlPlatform extends DoctrineMySqlPlatform implements EscapePolyfillInterface
{
    /**
     * Without an active connection cant use PDO Quote so this is a pollyfill
     * that can be used by SQL Formatter (Writer)
     * 
     * @param string $unescaped the string to escape
     * @return string 
     */ 
    public function quote($unescaped)
    {
        $replacements = array(
             "\x00"=>'\x00',
             "\n"=>'\n',
             "\r"=>'\r',
             "\\"=>'\\\\',
             "'"=>"\'",
             '"'=>'\"',
             "\x1a"=>'\x1a'
        );
        
        return strtr($unescaped,$replacements);
    }
    
}
/* End of Class */

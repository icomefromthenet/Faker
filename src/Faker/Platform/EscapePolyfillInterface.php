<?php
namespace Faker\Platform;

interface EscapePolyfillInterface
{
    /**
     * Without an active connection cant use PDO Quote so this is a pollyfill
     * that can be used by SQL Formatter (Writer)
     * 
     * @param string $unescaped the string to escape
     * @return string 
     */ 
    public function quote($unescaped);
    
}
/* End of class */
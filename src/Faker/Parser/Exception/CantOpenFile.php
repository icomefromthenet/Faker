<?php
namespace Faker\Parser\Exception;

use Faker\Parser\Exception as ParserException;

class CantOpenFile extends ParserException
{

    public function __construct($file) {
        $message = 'can not open file  ' . $file;
        parent::__construct($message);
    }

}
/* End of file */

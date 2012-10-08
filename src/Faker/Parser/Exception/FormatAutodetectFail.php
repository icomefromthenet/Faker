<?php
namespace Faker\Parser\Exception;

use Faker\Parser\Exception as ParserException;

class FormatAutodetectFail extends ParserException
{

    public function __construct($filename) {
        $message = 'autodetect failed';
        parent::__construct($message);
    }

}
/* End of file */
<?php
namespace Faker\Parser\Exception;

use Faker\Parser\Exception as ParserException;

class CantMakeTmpFile extends  ParserException
{

    public function __construct() {
        $message = 'can not make tmp file';
        parent::__construct($message);
    }

}
/* End of File */
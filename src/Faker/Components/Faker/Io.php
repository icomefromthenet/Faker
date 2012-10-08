<?php
namespace Faker\Components\Faker;

use Faker\Io\Io as Base;
use Faker\Io\IoInterface;

/*
 * class Io
 */
class Io extends Base implements IoInterface
{


    protected $dir = 'dump';


    /*
     * __construct()
     * @param string $base_folder the path to a project
     */

    public function __construct($base_folder) {
        parent::__construct($base_folder);
    }


}
/* End of File */

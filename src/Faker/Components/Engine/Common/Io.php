<?php
namespace Faker\Components\Engine\Common;

use Faker\Io\Io as Base;
use Faker\Io\IoInterface;

/*
 * class Io
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
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

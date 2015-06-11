<?php
namespace Faker\Components\Engine\Common\Datasource;

use Faker\Components\Config\DoctrineConnWrapper;

/**
 * A interface to assign extra database connection.
 *  * 
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.5
 */ 
interface ExtraConnectionInterface
{
    
    public function setExtraConnection(DoctrineConnWrapper $conn);
    
    public function getExtraConnection();
    
}
/* End of Interface */
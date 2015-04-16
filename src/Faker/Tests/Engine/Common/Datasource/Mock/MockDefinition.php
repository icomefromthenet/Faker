<?php
namespace Faker\Tests\Engine\Common\Datasource\Mock;

use Faker\Tests\Engine\Common\Datasource\Mock\MockDatasource;
use Faker\Components\Engine\Common\Datasource\AbstractDefinition;

class MockDefinition extends AbstractDefinition
{
    
    public function getNode()
    {
        return new MockDatasource();
    }
    
}
/* End of Class */
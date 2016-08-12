<?php
namespace Faker\Tests\Engine\XML\Mock;

use Faker\Components\Engine\Common\Datasource\AbstractDefinition;

class MockDefinition extends AbstractDefinition
{
    
    public function getNode()
    {
        return new MockPHPSource();
    }
    
}
/* End of Class */
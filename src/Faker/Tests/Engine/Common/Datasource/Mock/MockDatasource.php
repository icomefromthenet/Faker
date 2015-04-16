<?php
namespace Faker\Tests\Engine\Common\Datasource\Mock;

use Faker\Components\Engine\Common\Datasource\DatasourceInterface;
use Faker\Components\Engine\Common\Datasource\AbstractDatasource;

class MockDatasource extends AbstractDatasource implements DatasourceInterface
{
 
    public function initSource()
    {
        
    }
    
    public function fetchOne()
    {
        
    }
    
    public function flushSource()
    {
        
    }
    
    public function cleanupSource()
    {
        
    }
    
    
    public function getConfigTreeExtension(NodeDefinition $rootNode)
    {
        return $rootNode;
    }
}
/* End of Class */
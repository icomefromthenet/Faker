<?php
namespace Faker\Tests\Engine\Common\Datasource\Mock;

use Faker\Components\Engine\Common\Datasource\DatasourceInterface;
use Faker\Components\Engine\Common\Datasource\AbstractDatasource;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

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
        $rootNode->children()
                ->scalarNode('badOption')
                    ->treatNullLike('en')
                    ->defaultValue('en')
                    ->info('Abad option')
                    ->validate()
                        ->ifTrue(function($v){
                            return $v === 'bad';
                        })
                        ->then(function($v){
                            throw new InvalidConfigurationException('BadOption is equal to bad');
                        })
                    ->end()
                ->end();
        
        return $rootNode;
    }
}
/* End of Class */
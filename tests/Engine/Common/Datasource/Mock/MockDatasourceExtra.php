<?php
namespace Faker\Tests\Engine\Common\Datasource\Mock;

use Faker\Components\Config\DoctrineConnWrapper;
use Faker\Components\Engine\Common\Datasource\DatasourceInterface;
use Faker\Components\Engine\Common\Datasource\AbstractDatasource;
use Faker\Components\Engine\Common\Datasource\ExtraConnectionInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


class MockDatasourceExtra extends AbstractDatasource implements DatasourceInterface,ExtraConnectionInterface
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
    
    //--------------------------------------------------------------------------
    # ExtraConnectionInterface
    
    protected $extraConnection;
    
    public function setExtraConnection(DoctrineConnWrapper $conn)
    {
        $this->extraConnection  = $conn;
    }
    
    public function getExtraConnection()
    {
        return $this->extraConnection;
    }
}
/* End of Class */
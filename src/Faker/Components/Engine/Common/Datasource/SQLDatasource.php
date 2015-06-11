<?php
namespace Faker\Components\Engine\Common\Datasource;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

use Faker\Components\Engine\Common\Utilities;
use Faker\Locale\LocaleInterface;
use Faker\Components\Templating\Loader;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\OptionInterface;
use Doctrine\DBAL\Connection;
use PHPStats\Generator\GeneratorInterface;

/**
  * Datasource that contains a dataset from a sql query
  * 
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.5
  */
class SQLDatasource extends AbstractDatasource
{
    
    
    
    //--------------------------------------------------------------------------
    # Config Sub System
    
    
    public function getConfigTreeExtension(NodeDefinition $rootNode)
    {
        
        $rootNode->children()
                
                ->scalarNode('query')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('Sql query to execute return single row')
                ->end()
                
                ->scalarNode('connection')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('Connection name as defined in config file')
                ->end()
                
            ->end();
            
        return $rootNode;
    }
    
   
    
    //--------------------------------------------------------------------------
    # DatasourceInterface 
    
    public function initSource()
    {
        # prepare query from config param        
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
    
}
/* End of file */
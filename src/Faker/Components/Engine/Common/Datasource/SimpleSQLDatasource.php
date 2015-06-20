<?php
namespace Faker\Components\Engine\Common\Datasource;

use \PDO;
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
use Faker\Components\Config\DoctrineConnWrapper;
use Faker\Components\Writer\Limit;

/**
  * Datasource that contains a dataset from a sql query
  * 
  *  Will fetch a new row for each execution, useful when using a database query that fetch one row at random
  *  unlink BulkSQLDatastore this type does not accept limit or offset up to user to construct the correct query
  *  for the given platform.
  * 
  *  e. g SELECT A FROM DUAL ORDER BY RANDOM() LIMIT 1;
  * 
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.5
  */
class SimpleSQLDatasource extends AbstractDatasource implements ExtraConnectionInterface
{
    
    /**
     *  @var Faker\Components\Config\DoctrineConnWrapper
     */ 
    protected $extraDBConnection;
    
    /**
     * @ Doctrine\DBAL\Statement
     */ 
    protected $STH;
   
    
    
    protected function executeQuery()
    {
        # execute the query
        $this->STH->execute();
        
        $aResult = $this->STH->fetch(PDO::FETCH_ASSOC);
        
        if(true === empty($aResult)) {
            throw new EngineException(sprintf('The datasoruce with id %s using connection %s query query returned an empty result set',$this->getOption('id'),$this->getOption('connection')));
        }
        
        return $aResult;
    }
    
    
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
        $oDb = $this->getExtraConnection();
        
        $query  = $this->getOption('query');
        
        # prepare the sth for execution
        $this->STH = $oDb->prepare($query,array());
        
    }
    
    public function fetchOne()
    {
        # We have a bulk set but no result is cached
        $r = $this->executeQuery();
        
        return $r;
    }
    
    public function flushSource()
    {
        
    }
    
    public function cleanupSource()
    {
        
        if($this->STH) {
            $this->STH->closeCursor();
            unset($this->STH);
        }

    }
    
    
    //--------------------------------------------------------------------------
    # ExtraConnectionInterface
    
    public function setExtraConnection(DoctrineConnWrapper $conn)
    {
        $this->extraDBConnection = $conn;
    }
    
    public function getExtraConnection()
    {
        return $this->extraDBConnection;
    }
}
/* End of file */
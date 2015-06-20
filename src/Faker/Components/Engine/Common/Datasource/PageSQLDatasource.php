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
  * Datasource that contains a dataset from a sql query, this datastore will iterate
  * over the result set and will when end is reached it will fetch next set of values.
  * this datastore accept limit and offset param and use Doctrine to construct a portable
  * query.
  * 
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.5
  */
class PageSQLDatasource extends AbstractDatasource implements ExtraConnectionInterface
{
    
    /**
     *  @var Faker\Components\Config\DoctrineConnWrapper
     */ 
    protected $extraDBConnection;
    
    /**
     * @ Doctrine\DBAL\Statement
     */ 
    protected $STH;
    
    /**
     * @var result struct
     */ 
    protected $aResult;
    
    /**
     * @integer index inside the result
     */ 
    protected $oResultLimit;
    
    /**
     * @var page number currently on
     */ 
    protected $iPageNumber;
    
    protected function executeQuery()
    {
        # execute the query
        $this->STH->execute();
        
        $aResult = $this->STH->fetchAll(PDO::FETCH_ASSOC);
        
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
                
                ->integerNode('limit')
                    ->defaultValue(1)
                    ->min(1)
                ->end()
                
                ->integerNode('offset')
                    ->defaultValue(0)
                    ->min(0)
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
        $limit  = $this->getOption('limit');
        $offset = $this->getOption('offset');
        
        
        # bind the limit using the plaform
        $sFinalQuery = $oDb->getDatabasePlatform()->modifyLimitQuery($query,$limit,$offset);
        
        # prepare the sth for execution
        $this->STH = $oDb->prepare($sFinalQuery,array());
        
        # make sure the result set is empty
        $this->aResult = null;
        
        $this->oResultLimit = new Limit($limit);
        
        $this->iPageNumber = 1;
        
        
    }
    
    public function fetchOne()
    {
        
        if($this->aResult === null) {
            # execute the first query to fill the cache with first page of data
            $this->aResult = $this->executeQuery();
        }
        
        # if value does not exists (maybe query returned less then limit) need to reset the iterator
        if(false === isset($this->aResult[$this->oResultLimit->currentAt()])) {
                $this->oResultLimit->reset();
            
                # increment the page number
                $this->iPageNumber = $this->iPageNumber + 1;
            
                # build a new query
                $oDb    = $this->getExtraConnection();
                $query  = $this->getOption('query');
                $limit  = $this->getOption('limit');
                $offset = $this->getOption('offset') + ($this->getOption('limit') * ($this->iPageNumber -1));
                
                # close the statement to free up resources
                $this->STH->closeCursor();
                unset($this->STH);
            
                # prepare the next STH for execution
                $sFinalQuery = $oDb->getDatabasePlatform()->modifyLimitQuery($query,$limit,$offset);
                $this->STH = $oDb->prepare($sFinalQuery,array());
                
                # execute the query
                $this->aResult = $this->executeQuery();
                
                $r = $this->aResult[0];
                $this->oResultLimit->increment();
                            
            } else {
            
                $r = $this->aResult[$this->oResultLimit->currentAt()];
                $this->oResultLimit->increment();
            
            }
    
        
        return $r;
    }
    
    public function flushSource()
    {
        unset($this->aResult);
        $this->oResultLimit->reset();
    }
    
    public function cleanupSource()
    {
        
        if($this->STH) {
            $this->STH->closeCursor();
            unset($this->STH);
            unset($this->oResultLimit);
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
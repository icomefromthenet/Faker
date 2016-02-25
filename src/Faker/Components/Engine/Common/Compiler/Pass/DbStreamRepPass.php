<?php
namespace Faker\Components\Engine\Common\Compiler\Pass;

use Faker\Components\Engine\Common\Compiler\CompilerPassInterface;
use Faker\Components\Engine\Common\Compiler\CompilerInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Config\ConnectionPool;
use Faker\Components\Engine\Common\Composite\FormatterNode;
use Faker\Components\Engine\Common\Formatter\Sql;
use Faker\Components\Engine\Common\Formatter\FormatterFactory;
use Faker\Components\Engine\EngineException;
use Faker\Components\Writer\Manager;
    
/*
 * This will replace any formatters that have a connection options to use
 * database streams instead of the default settings.
 *
 * Using a complier to ensure that the formatter has validated the connection
 * as non empty value (though it won't check if connection exists).
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.5
 */
class DbStreamRepPass implements CompilerPassInterface
{
    
    /**
     * @var Faker\Components\Engine\Config\ConnectionPool
     */ 
    protected $dbPool;
    
    /**
     *  @var Faker\Components\Writer\Manager
     */ 
    protected $writerManager;
    
    public function __construct(ConnectionPool $dbPool,Manager $writerManager)
    {
        $this->dbPool        = $dbPool;
        $this->writerManager = $writerManager;
    }
    
    
    /**
      *  This will map DatasourceNodes into the dependent Table/Types Composite Nodes
      *
      *  @param CompositeInterface $composite
      *  @param CompilerInterface  $cmp
      *  @throws EngineException if the connection can not be found
      */
    public function process(CompositeInterface $composite,CompilerInterface $cmp)
    {
        # schema node assume children could be formatters
        foreach($composite->getChildren() as $child) {
            
            if($child instanceof FormatterNode) {
                $formatter = $child->getInternal();    
                
                if($formatter->hasOption(Sql::CONFIG_WRITE_TO_DATABASE)) {
                    $connectionName = $formatter->getOption(Sql::CONFIG_WRITE_TO_DATABASE);
                    
                    # does the connection exist in the pool            
                    if(false === $this->getConnectionPool()->hasExtraConnection($connectionName)) {
                        throw new EngineException("The database connection at name::$connectionName cant not be matched to a connection in config");
                    }
                    
                    # fetch the extra connection and lookup the correct formatter short name from the factory
                    $conn               = $this->getConnectionPool()->getExtraConnection($connectionName);
                    $formatterShortName = FormatterFactory::reverseLookup(get_class($formatter));
                    
                    if(true === $conn->getFakerReadOnlyConnection()) {
                        throw new EngineException("The $connectionName is set to read only unable to create Database Stream Writer");
                    }
    
                    # instance new stream using the manager and switch over
                    $dbStream = $this->getWriterManager()
                                     ->getDatabaseStream($formatter->getPlatform()->getName(),$formatterShortName,$conn);
                                     
                    $dbStream->setDatabase($conn);
                    
                    $formatter->getWriter()->setStream($dbStream);
                    
                }
            }
        }
        
    }
    
    /**
     * Return the assigned database connection pool
     * 
     * @return Faker\Components\Engine\Config\ConnectionPool
     * @access public
     */ 
    public function getConnectionPool()
    {
        return $this->dbPool;
    }
    
    /**
     * Return the writter component manager property
     * 
     * @return Faker\Components\Writer\Manager
     */ 
    public function getWriterManager()
    {
        return $this->writerManager;
    }
}
/* End of File */
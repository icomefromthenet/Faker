<?php
namespace Faker\Components\Engine\Common\Compiler\Pass;

use Faker\Components\Engine\Common\Compiler\CompilerPassInterface;
use Faker\Components\Engine\Common\Compiler\CompilerInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Visitor\DSourceInjectorVisitor;
use Faker\Components\Config\ConnectionPool;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Datasource\ExtraConnectionInterface;


    
/*
 * Execute the datasource injector visitor, this will map Datasource Composite Nodes into
 * the dependent Table/Types Composite Nodes where a FromSource Type Exists in a TypeNode.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.5
 */
class DatasourcePass implements CompilerPassInterface
{
    
    /**
     * @var Faker\Components\Engine\Common\Visitor\DSourceInjectorVisitor
     */ 
    protected $visitor;
    
    /**
     * @ Faker\Components\Config\ConnectionPool
     */ 
    protected $pool;
    
    /**
     * @var a collection ids for inited datasources
     */ 
    protected $aInitCollection;
    
    
    
    public function __construct(DSourceInjectorVisitor $visitor, ConnectionPool $pool)
    {
        $this->visitor         = $visitor;
        $this->pool            = $pool;
        $this->aInitCollection = array();
    }
    
    
    /**
      *   This will map DatasourceNodes into the dependent Table/Types Composite Nodes
      *
      *  @param CompositeInterface $composite
      *  @param CompilerInterface  $cmp
      */
    public function process(CompositeInterface $composite,CompilerInterface $cmp)
    {
        
        # inject datasources into composite
        $composite->acceptVisitor($this->getSourceVisitor());
        
        # inject connections into our datasources
        $sourceList = $this->getSourceVisitor()->getResult();
        
        
        
        foreach($sourceList as $datasourceName => $datasourceCompositeNode) {
            
            
            $dataSource = $datasourceCompositeNode->getDatasource();    
            
            
            //fetch the connection name from the internal source
            if(true === $dataSource->hasOption('connectionName') 
                    && $dataSource instanceof ExtraConnectionInterface) {
                
                $connectionName = $dataSource->getOption('connectionName'); 
                
                // find connection in pool
                if (false === $this->pool->hasExtraConnection($connectionName)) {
                    throw new EngineException(sprintf('Connection pool does not have connection %s',$connectionName));
                }
                
                // assign connection to the datasource
                $dataSource->setExtraConnection($this->pool->getExtraConnection($connectionName));
                
               
                
            }
            
            
            
            $sHash = md5(\spl_object_hash($dataSource));
            
            if(false === \in_array($sHash,$this->aInitCollection)) {
                $this->aInitCollection[] = $sHash;
                
                // int the source to build the query
                $dataSource->initSource();
            }
            
        }
        
    }
    
    /**
     * Return the assigned visitor
     * 
     * @return Faker\Components\Engine\Common\Visitor\DSourceInjectorVisitor
     * @access public
     */ 
    public function getSourceVisitor()
    {
        return $this->visitor;
    }
}
/* End of File */
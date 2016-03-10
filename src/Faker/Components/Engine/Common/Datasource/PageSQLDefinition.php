<?php
namespace Faker\Components\Engine\Common\Datasource;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Locale\LocaleInterface;
use Faker\Components\Templating\Loader;
use Faker\Components\Engine\Common\Builder\TypeDefinitionInterface;
use Faker\Components\Engine\Common\Builder\NodeInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\EngineException;
use Doctrine\DBAL\Connection;
use PHPStats\Generator\GeneratorInterface;


/**
  *  Definition For PageSQL Datasources 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.5
  */
class PageSQLDefinition extends AbstractDefinition
{
    
    
    /**
      *  Fetch the node managed by this definition
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    public function getNode()
    {
        $source = new PageSQLDatasource();
      
        return $source;
    }
    
    /**
     * Sets the query for database
     *
     * 
     * @access  public
     * @return  PageSqlDefinition
     * @param   string    $sQuer  The database query to run
     */ 
    public function setQuery($sQuery)
    {
        $this->attribute('query',$sQuery);
        
        return $this;
    }
    
    /**
     * Sets the connection name for this source.
     * 
     * This should be defined in the connection file.
     * 
     * @access  public
     * @return  PageSqlDefinition
     * @param   string    $sName  The database name
     */ 
    public function setConnectionName($sName)
    {
        $this->attribute('connectionName',$sName);
        
        return $this;
    }
    
    /**
     * Sets the query offset
     * 
     * This should be defined in the connection file.
     * 
     * @access  public
     * @return  PageSqlDefinition
     * @param   integer    $iOffset  The offset to start with
     */
    public function setOffset($iOffset)
    {
        $this->attribute('offset',$iOffset);
        
        return $this;
    }
    
    /**
     * Sets the query limit
     * 
     * This should be defined in the connection file.
     * 
     * @access  public
     * @return  PageSqlDefinition
     * @param   integer    $iLimit  The query limit per iteration
     */
    public function setLimit($iLimit)
    {
        $this->attribute('limit',$iLimit);
        
        return $this;
    }
    
    public function endPageSQLSource()
    {
        return $this->end();
    }
   
}
/* End of File */
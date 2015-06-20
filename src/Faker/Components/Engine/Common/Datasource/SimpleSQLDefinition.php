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
  *  Definition For Simple SQL Datasources 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.5
  */
class SimpleSQLDefinition extends AbstractDefinition
{
    
    
    /**
      *  Fetch the node managed by this definition
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    public function getNode()
    {
        $source = new SimpleSQLDatasource();
      
        return $source;
    }
    
    /**
     * Sets the query for database
     * 
     * This query should provide a limit and some sort of offset best used
     * with a statement link 'SELECT * FROM table ORDER BY RANDOM() LIMIT 1'
     * 
     * @access  public
     * @return  SimpleSqlDefinition
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
     * @return  SimpleSqlDefinition
     * @param   string    $sName  The database name
     */ 
    public function setConnectionName($sName)
    {
        $this->attribute('connection',$sName);
        
        return $this;
    }
    
   
}
/* End of File */
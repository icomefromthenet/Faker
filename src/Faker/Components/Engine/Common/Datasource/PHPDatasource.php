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
  * Datasource that contains a fixed dataset. 
  * 
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.5
  */
class PHPDatasource extends AbstractDatasource
{
    
    protected $dataIterator;
    
    /**
     * Return the full dataset assigned by the implementer;
     * 
     * @return Iterator
     */ 
    public function getIterator()
    {
        return  $this->dataIterator;
    }
    
    /**
     * Sets the iterator to use
     * 
     * @param Iterator the data iterator to use
     */ 
    public function setIterator(\Iterator $it)
    {
        $this->dataIterator = $it;
    }
    
    //--------------------------------------------------------------------------
    # Config Sub System
    
    
    public function getConfigTreeExtension(NodeDefinition $rootNode)
    {
        return $rootNode;
    }
    
    public function validate()
    {
        parent::validate();
        
        if(true == empty($this->dataIterator)) {
            throw new EngineException('PHPDatasource must have some data assigned');
        }
    }
    
    //--------------------------------------------------------------------------
    # DatasourceInterface 
    
    
    /**
     * This init method is called before any generation is commenced
     * 
     * Can be used to int database connections
     * 
     * @access public
     * @return void
     */ 
    public function initSource()
    {
        $dsource = $this->getIterator();
        
        if(!$dsource instanceof \Iterator) {
            throw new EngineException('The datasource must be an iterator');
        }
    }
    
    /**
     * Called during the generator execution, for each needed row
     * 
     * Where fetch a row of data, from interal cache or a database call
     * 
     * @access public
     * @return array of data using a hash index
     */ 
    public function fetchOne()
    {
        return $this->getIterator()->current();
    }
    
    /**
     * This method is called when the node the
     * source is referenced in has finished its processing
     * 
     * @access public
     * @return void
     */ 
    public function flushSource()
    {
        return $this->getIterator()->next();
    }
    
    /**
     * Called when the source is no longer needed by any nodes, usually
     * at the end of a generation run.
     * 
     * Can be used to cleanup database references, etc...
     * 
     * @access public
     * @return void
     */ 
    public function cleanupSource()
    {
        $this->getIterator()->rewind();
    }
    
}
/* End of file */
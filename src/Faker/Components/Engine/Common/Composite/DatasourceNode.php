<?php
namespace Faker\Components\Engine\Common\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Datasource\DatasourceInterface;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;

/**
  *  Node to contain datasources, that as this node is a wrapper over a datasource
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class DatasourceNode extends GenericNode implements CompositeInterface, GeneratorInterface, VisitorInterface, HasDatasourceInterface
{

    
    /**
     * @var array[Faker\Components\Engine\Common\Datasource\DatasourceInterface]
     */ 
    protected $datasources;
    
    
    
    
    public function __construct($id, EventDispatcherInterface $event, DatasourceInterface $datasource)
    {
        parent::__construct($id,$event);
        $this->datasources    = $datasource;
        
    }
    
    //------------------------------------------------------------------
    # DatasourcesInterface
    
    
    /**
      *  Return  assigned datasources.
      *
      *  @return Faker\Components\Engine\Common\Datasource\DatasourceInterface 
      *  @access public
      */
    public function getDatasource()
    {
        return $this->datasources;
    }

    /**
      *  Assign datasource to this composite node.
      *
      *  @param Faker\Components\Engine\Common\Datasource\DatasourceInterface a source to add
      *  @access public
      */
    public function setDatasource(DatasourceInterface $source)
    {
        $this->datasources= $source;
    }
   
   
   //------------------------------------------------------------------
   # GeneratorInterface
    
    
    
    public function generate($rows, &$values = array(),$last = array())
    {
        return $values;
    }
    
    
    //------------------------------------------------------------------
    # VisitorInterface
    
    public function acceptVisitor(BasicVisitor $visitor)
    {
        
        # accept visitors
        
        
        return $visitor;
    }
    
    
    // --------------------------------------------------
    
    public function addChild(CompositeInterface $child)
    {
        throw new EngineException('This node does not allow children');
    }
    
}
/* End of File */
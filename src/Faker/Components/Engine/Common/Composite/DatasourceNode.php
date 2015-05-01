<?php
namespace Faker\Components\Engine\Common\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Datasource\DatasourceInterface;

/**
  *  Node to contain datasources, that as this node is a wrapper over a datasource it does not 
  *  implement the DatasourcesInterface, as it only have 1 source passed in via the constructor/setter.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class DatasourceNode extends GenericNode implements CompositeInterface, GeneratorInterface, VisitorInterface, HasDatasourceInterface
{
    
    /**
     * @var Faker\Components\Engine\Common\Datasource\DatasourceInterface;
     */ 
    protected $datasource;
    
    
    
    public function __construct($id, EventDispatcherInterface $event, DatasourceInterface $datasource)
    {
        parent::__construct($id,$event);
        $this->datasource   = $datasource;
    }
    
    
    /**
     *  Return the bound datasource
     * 
     * @return Faker\Components\Engine\Common\Datasource\DatasourceInterface;
     */ 
    public function getDatasource()
    {
        return $this->datasource;
    }
   
   /*
    * Set this nodes datasource
    * 
    * @return this
    * @parm Faker\Components\Engine\Common\Datasource\DatasourceInterface $source   The datasource to assign
    */
    public function setDatasource(DatasourceInterface $source)
    {
        $this->datasource = $source;
        
        return $this;
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
        $children = $this->getChildren();
        
        $visitor->visitDirectedGraphBuilder($this);
        
        foreach($children as $child) {
            if($child instanceof VisitorInterface) {
                $child->acceptVisitor($visitor);                    
            }
        }
        
        return $visitor;
    }
    
    
    // --------------------------------------------------

    public function addChild(CompositeInterface $child)
    {
        throw new EngineException('This node does not allow children');
    }
    
    
   /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
	    $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('config');
	
	    # get child custom config options
	    $this->getConfigTreeExtension($rootNode);
	
        return $treeBuilder;  
    }
    
}
/* End of File */
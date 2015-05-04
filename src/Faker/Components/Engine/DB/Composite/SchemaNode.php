<?php
namespace Faker\Components\Engine\DB\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\GeneratorCache;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\GeneratorInterface;
use Faker\Components\Engine\Common\Composite\VisitorInterface;
use Faker\Components\Engine\Common\Composite\SchemaNode as BaseSchemaNode;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;
use Faker\Components\Engine\Common\Composite\HasDatasourceInterface;
use Faker\Components\Engine\Common\BuildEvents;
use Faker\Components\Engine\Common\Formatter\FormatEvents;


/**
  *  Schema Node implements GeneratorInterface, VisitorInterface on top of the schema node
  *  from the common namespace.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class SchemaNode extends BaseSchemaNode implements GeneratorInterface, VisitorInterface
{
    /**
      *  @var  Faker\Components\Engine\Common\GeneratorCache
      */
    protected $resultCache;
    
    
    
    //------------------------------------------------------------------
    # GeneratorInterface
    
    public function generate($rows,&$values = array(),$last = array())
    {
        $id          = $this->getId(); 
        $event       = $this->getEventDispatcher();
        $children    = $this->getChildren();
        
        
        # start the events
        $event->dispatch(
               FormatEvents::onSchemaStart,
               new GenerateEvent($this,array(),$id)
        );
        
        foreach($children as $child) {
            
            # generate data for each table
            if($child instanceof GeneratorInterface) {
                $child->generate($rows,$values,array());    
            }
            
            # call cleanup on each datasource
            if($child instanceof HasDatasourceInterface) {
                $child->getDatasource()->cleanupSource();
            }
            
        }
        
       $event->dispatch(
               FormatEvents::onSchemaEnd,
               new GenerateEvent($this,array(),$id)
        );
        
        return $values;
    }
    
    public function setResultCache(GeneratorCache $cache)
    {
        $this->resultCache = $cache;
    }
    

    public function getResultCache()
    {
        return $this->resultCache;
    }
    
    //------------------------------------------------------------------
    # VisitorInterface

    public function acceptVisitor(BasicVisitor $visitor)
    {
        # execute visitors that apply to this node
        $visitor->visitDirectedGraphBuilder($this);
        
        # execute accept on children
        $children = $this->getChildren();
        
        foreach($children as $child) {
            if($child instanceof VisitorInterface) {
                $child->acceptVisitor($visitor);
            }
        }
        
        return $visitor;
    }
  
}
/* End of File */
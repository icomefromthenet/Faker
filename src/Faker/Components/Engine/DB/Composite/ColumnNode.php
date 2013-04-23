<?php
namespace Faker\Components\Engine\DB\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\GeneratorCache;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\GeneratorInterface;
use Faker\Components\Engine\Common\Composite\VisitorInterface;
use Faker\Components\Engine\Common\Composite\ColumnNode as BaseColumnNode;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;


/**
  *  Column Node implements GeneratorInterface, VisitorInterface on top of the base node
  *  from common namespace.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ColumnNode extends BaseColumnNode implements GeneratorInterface, VisitorInterface
{
    
     /**
      *  @var  Faker\Components\Engine\Common\GeneratorCache
      */
    protected $resultCache;
    
    
     //------------------------------------------------------------------
    # GeneratorInterface
    
    public function generate($rows,$values = array())
    {
        $id       = $this->getId();
        $event    = $this->getEventDispatcher();
        $children = $this->getChildren();
        $cache    = $this->getResultCache();
        
        
        # dispatch the start event
        
        $event->dispatch(
                        FormatEvents::onColumnStart,
                        new GenerateEvent($this,$values,$id)
        );
        
        # send the generate command to the type
        $value = array();
        
        foreach($children as $type) {
                         
            # if we have many types we concatinate
            $value[] = $type->generate($rows,$values);
        
            # dispatch the generate event
            $event->dispatch(
                FormatEvents::onColumnGenerate,
                new GenerateEvent($this,array( $id => $value ),$id)
            );
        }
        
        # assign the value to the struct, check if only one value
        # if one value we want to keep the type the same
        
        if(count($value) > 1) {
            $values[$id] = implode('',$value); # join as a string 
        } else {
            $values[$id] = $value[0]; 
        }
        
        # test if the value needs to be cached
        if($cache instanceof GeneratorCache) {
            $cache->add($values[$id]);
        }
        
        # dispatch the stop event
        $event->dispatch(
                FormatEvents::onColumnEnd,
                new GenerateEvent($this,$values,$id)
        );
        
        # return values so they can be grouped in table parent
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
    /**
      *  Accept a visitor
      *
      *  @return void
      *  @access public
      *  @param BasicVisitor $visitor the visitor to accept
      */
    public function acceptVisitor(BasicVisitor $visitor)
    {
        # execute visitors that apply to this node
        $visitor->visitDBALGatherer($this);
        
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
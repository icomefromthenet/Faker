<?php
namespace Faker\Components\Engine\Common\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\GeneratorCache;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\GeneratorInterface;
use Faker\Components\Engine\Common\Composite\VisitorInterface;
use Faker\Components\Engine\Common\Composite\SchemaNode as BaseSchemaNode;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;


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
    
    public function generate($rows,$values = array())
    {
        
        $this->getEventDispatcher()->dispatch(FormatEvents::onRowStart,new GenerateEvent($this,$values,null));
        
        foreach($this->getChildren() as $child) {
            if($child instanceof GeneratorInterface) {
                $child->generate($rows,$values);    
            }
        }
        
        $this->getEventDispatcher()->dispatch(FormatEvents::onRowEnd,new GenerateEvent($this,$values,null));
                
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
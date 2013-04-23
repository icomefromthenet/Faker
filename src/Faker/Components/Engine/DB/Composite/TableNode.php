<?php
namespace Faker\Components\Engine\DB\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\GeneratorCache;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\GeneratorInterface;
use Faker\Components\Engine\Common\Composite\VisitorInterface;
use Faker\Components\Engine\Common\Composite\TableNode as BaseTableNode;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;


/**
  *  Table Node implements GeneratorInterface, VisitorInterface on top of the base node
  *  from the common namespace.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class TableNode extends BaseTableNode implements GeneratorInterface, VisitorInterface
{
     
    /**
      *  @var  Faker\Components\Engine\Common\GeneratorCache
      */
    protected $resultCache;

     //------------------------------------------------------------------
    # GeneratorInterface
    
    public function generate($rows,$values = array())
    {
         # dispatch the start table event
       
        $this->event->dispatch(
                FormatEvents::onTableStart,
                new GenerateEvent($this,$values,$this->getOption('name'))
        );
   
   
        do {
                
                # reset values for next row run.
                
                $values = array();
                
                # dispatch the row start event
            
                $this->event->dispatch(
                    FormatEvents::onRowStart,
                    new GenerateEvent($this,$values,$this->getOption('name'))
                );

                # send the generate event to the columns
       
                foreach($this->child_types as $type) {
                    $values = $type->generate($rows,$values);            
                }
        
                
                # dispatch the row stop event
                
                $this->event->dispatch(
                    FormatEvents::onRowEnd,
                    new GenerateEvent($this,$values,$this->getOption('name'))
                );

                    
                # increment the rows needed by datatypes. 
                $rows = $rows +1;
        }
        while($rows <= $this->rows);
        
        
        # dispatch the stop table event
        
        $this->event->dispatch(
                    FormatEvents::onTableEnd,
                    new GenerateEvent($this,$values,$this->getOption('name'))
        );

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
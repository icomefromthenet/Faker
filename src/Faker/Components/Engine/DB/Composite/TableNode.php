<?php
namespace Faker\Components\Engine\DB\Composite;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Composite\CompositeException;
use Faker\Components\Engine\Common\GeneratorCache;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\GeneratorInterface;
use Faker\Components\Engine\Common\Composite\VisitorInterface;
use Faker\Components\Engine\Common\Composite\TableNode as BaseTableNode;
use Faker\Components\Engine\Common\Formatter\FormatEvents;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;
use Faker\Components\Engine\Common\Visitor\BasicVisitor;
use Faker\Components\Engine\Common\Composite\HasDatasourceInterface;


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

    /**
      *  @var integer the number of rows to generate 
      */
    protected $rowsToGenerate;
    
    /**
      *  Return the number of rows that this table node will generate
      *
      *  @access public
      *  @return integer
      */
    public function getRowsToGenerate()
    {
        return $this->rowsToGenerate;
    }
    
    /**
      *  Sets the number of rows to generate
      *
      *  @access public
      *  @param integer $value the number of rows to generate for this table
      */
    public function setRowsToGenerate($value)
    {
        $this->rowsToGenerate = $value;
    }
    
    
    public function validate()
    {
        $rowsToGenerate = $this->getRowsToGenerate();
        
        if(is_integer($rowsToGenerate) === false) {
            throw new CompositeException($this,'rows to generate must be an integer');
        }
        
        if($rowsToGenerate <= 0) {
            throw new CompositeException($this,'rows to generate must be > 0');
        }
        
        parent::validate();
        
    }
    
     //------------------------------------------------------------------
    # GeneratorInterface
    
    public function generate($rows,&$values = array(),$last = array())
    {
        $id         = $this->getId();
        $children   = $this->getChildren();
        $event      = $this->getEventDispatcher();
        $toGenerate = $this->getRowsToGenerate();
        
         # dispatch the start table event
       
        $event->dispatch(
                FormatEvents::onTableStart,
                new GenerateEvent($this,$values,$id)
        );
   
        do {
                
                # reset values for next row run.
                
                $values = array();
                
                # dispatch the row start event
            
                $event->dispatch(
                    FormatEvents::onRowStart,
                    new GenerateEvent($this,$values,$id)
                );

                # send the generate event to the columns
                # execute fetch data for each source
       
                foreach($children as $node) {
                    if($node instanceof HasDatasourceInterface) {
                        $node->getDatasource()->fetchOne();
                    }
                }
            
                foreach($children as $node) {
                    if($node instanceof GeneratorInterface) {
                        $node->generate($rows,$values,$last);                
                    }
                }
                
                $last = $values;
                
                
                foreach($children as $node) {
                    if($node instanceof HasDatasourceInterface) {
                        $node->getDatasource()->flushSource();
                    }
                }
                
                # dispatch the row stop event
                
                $event->dispatch(
                    FormatEvents::onRowEnd,
                    new GenerateEvent($this,$values,$id)
                );

                    
                # increment the rows needed by datatypes. 
                $rows = $rows +1;
        }
        while($rows <= $toGenerate);
        
        
        # dispatch the stop table event
        
        $event->dispatch(
                    FormatEvents::onTableEnd,
                    new GenerateEvent($this,$values,$id)
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
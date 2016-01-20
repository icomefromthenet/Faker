<?php
namespace Faker\Components\Engine\Common\Builder;

use PHPStats\Generator\GeneratorInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\TypeRepository;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\GenericNode;


/**
  *  Builder that will compress child nodes into a single TypeNode.
  *
  *  This implements SelectorListInterface and FieldListInterface
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class TypeBuilder extends NodeBuilder implements SelectorListInterface, FieldListInterface
{
    
    
    public function endSwapAt()
    {
        return $this->end();
    }
    
    /**
      *  Create the field node that hold the child selectors and types.
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    public function getNode()
    {
        return new GenericNode($this->name,$this->eventDispatcher);
    }
    
    
    /**
    *  Wrap the child compositeNodes (Types and Selectors) into a single field node.  
    *
    *  @return NodeInterface The builder of the parent node
    *  @access public
    */
    public function end()
    {
        $node     = $this->getNode();
        $children = $this->children();
        $parent   = $this->getParent();
        
        foreach($children as $child) {
            $node->addChild($child);
        }
        
        # send the new field node to the parent;
        $parent->append($node);
        
        # return parent to continue chain.
        return $parent;
    }
    
}
/* End of File */
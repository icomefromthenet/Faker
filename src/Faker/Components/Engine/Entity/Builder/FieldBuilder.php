<?php
namespace Faker\Components\Engine\Entity\Builder;

use PHPStats\Generator\GeneratorInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Faker\Components\Engine\EngineException;
use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\Common\TypeRepository;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\Builder\ParentNodeInterface;
use Faker\Components\Engine\Common\Builder\DefaultTypeDefinition;
use Faker\Components\Engine\Common\Builder\FieldListInterface;
use Faker\Components\Engine\Common\Builder\SelectorListInterface;
use Faker\Components\Engine\Common\Builder\NodeBuilder;
use Faker\Components\Engine\Entity\Composite\FieldNode;
use Faker\Components\Engine\Common\Composite\CompositeInterface;

/**
  *  Builder to construct a single FieldNode.
  *
  *  This implements SelectorListInterface and FieldListInterface
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class FieldBuilder extends NodeBuilder implements SelectorListInterface, FieldListInterface
{
    
    /**
      *  Create the field node that hold the child selectors and types.
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    public function getNode()
    {
        return new FieldNode($this->name,$this->eventDispatcher);
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
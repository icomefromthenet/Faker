<?php
namespace Faker\Components\Engine\Entity\Builder;

use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Utilities;
use Faker\Components\Engine\Common\TypeRepository;
use Faker\Components\Engine\Common\Builder\NodeCollection;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PHPStats\Generator\GeneratorInterface;
use Doctrine\DBAL\Connection;

/**
  *  Collection of Fields
  *
  *  Will append these fields to the parent. Assume all children are FieldNodes.   
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class FieldCollection extends NodeCollection
{
    
    /**
      *  Add new Field to the entity.
      *
      *  @param string $name the fields name
      *  @return Faker\Components\Engine\Entity\Builder\FieldBuilder
      */
    public function addField($name)
    {
        $builder = new FieldBuilder($name,$this->eventDispatcher,$this->repo,$this->utilities,$this->generator,$this->locale,$this->database,$this->templateLoader);
        $builder->setParent($this);
        
        return $builder;
    }
    
    
    
    /**
      *  Fetch the generator composite node managed by this builder node
      *
      *  @access public
      *  @return Faker\Components\Engine\Common\Composite\CompositeInterface the new node
      */
    public function getNode()
    {
        return null;
    }
   
    /**
    * Append the fields to the parent builder for example an EntityGenerator.
    * Assume children are all FieldNodes. 
    *
    * @return \Faker\Components\Engine\Common\Builder\NodeInterface The builder of the parent node
    */
    public function end()
    {
        $children = $this->children();
        $parent   = $this->getParent();
        
        # append the compositeNode children to the parent builder.
        foreach($children as $child) {
            $parent->append($child);
        }
        
        # return parent to continue chain.
        return $parent;
    }
    
}
/* End of File */
<?php
namespace Faker\Components\Engine\DB\Builder;

use PHPStats\Generator\GeneratorInterface;

use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Builder\NodeCollection;
use Faker\Components\Engine\DB\Composite\TableNode;
use Faker\Components\Engine\DB\Composite\ColumnNode;
use Doctrine\DBAL\Types\Type;

/**
  *  Build a ColumnNode 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ColumnBuilder extends NodeCollection
{
    /**
      *  @var string the dbaltype used in type conversion
      */
    protected $dbalType;
    
    /**
      * Adds a new Field To Column
      *
      * @access public
      * @return Faker\Components\Engine\DB\Builder\FieldBuilder
      */
    public function addField()
    {
        $builder = new FieldBuilder('columnField',
                                   $this->eventDispatcher,
                                   $this->repo,
                                   $this->utilities,
                                   $this->generator,
                                   $this->locale,
                                   $this->database,
                                   $this->templateLoader);
        
        $builder->setParent($this);
        
        return $builder;
    }
    
    /**
     *  Return a foreign Key Builder
     *
     *  @access public
     *  @return Faker\Components\Engine\DB\Builder\ForeignKeyBuilder
     *
    */
    public function addForeignField()
    {
        $builder = new ForeignKeyBuilder();
        $builder->eventDispatcher($this->eventDispatcher);
        $builder->setParent($this);
        
        return $builder;
    }
    
    
    public function getNode()
    {
        $name  = $this->name;
        $event = $this->eventDispatcher;
        $node  = new ColumnNode($name,$event);
        
        # bind properties
        $node->setDBALType($this->dbalType);
        
        return $node;
    }
    
    
    /**
    * Build a TableNode and append all children columnNodes to it
    * before passing TableNode to a parent NodeCollection
    *
    * @return \Faker\Components\Engine\DB\Builder\TableBuilder
    * @access public
    */
    public function end()
    {
        $node     = $this->getNode();
        $children = $this->children();
        $parent   = $this->getParent();
        
        # add child columns to this ColumnNode        
        foreach($children as $child) {
            $node->addChild($child);
        }
        
        # appent ColumnNode to parent builder
        $parent->append($node);
        
        return $parent;
        
    }
    
    
    /**
      *  Set the default locale on this table
      *
      *  @access public
      *  @param LocaleInterface $locale
      *  @return ColumnBuilder
      */
    public function defaultLocale(LocaleInterface $locale)
    {
        $this->locale = $locale;
        
        return $this;
    }
    
    /**
      *  Sets the default random generator to use
      *
      *  @access public
      *  @param GeneratorInterface $random
      *  @return ColumnBuilder
      */
    public function defaultGenerator(GeneratorInterface $random)
    {
        $this->generator = $random;
        
        return $this;
    }
    
    /**
      *  Sets the DBALType name to be used to convert
      *  the php value to database representation
      *
      *  @access public
      *  @return ColumnBuilder
      */
    public function dbalType($dbalTypeName)
    {
        if(Type::hasType($dbalTypeName) === false) {
            throw new EngineException('The doctrine DBAL Type::'.$dbalTypeName.' does not exist');
        }
        
        $this->dbalType = Type::getType($dbalTypeName);
        
        return $this;
    }
    
}
/* End of File */
<?php
namespace Faker\Components\Engine\DB\Builder;

use PHPStats\Generator\GeneratorInterface;

use Faker\Locale\LocaleInterface;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Builder\NodeCollection;
use Faker\Components\Engine\DB\Composite\TableNode;
use Faker\Components\Engine\DB\Composite\ColumnNode;


/**
  *  Build a TableNode 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class TableBuilder extends NodeCollection
{
    /**
      *  @var integer the number of rows to generate 
      */
    protected $toGenerate;
    
    /**
      *  Adds a new column to the table
      *
      * @access public
      * @param string the table database name
      * @return Faker\Components\Engine\DB\Builder\ColumnBuilder
      */
    public function addColumn($name)
    {
        $builder = new ColumnBuilder($name,
                                     $this->eventDispatcher,
                                     $this->repo,
                                     $this->utilities,
                                     $this->generator,
                                     $this->locale,
                                     $this->database,
                                     $this->templateLoader
                                );
                                    
        $builder->setParent($this);
        
        return $builder;
    }
    
    
    public function getNode()
    {
        $name  = $this->name;
        $event = $this->eventDispatcher;
        
        $node = new TableNode($name,$event);
        
        # bind properties
        $node->setRowsToGenerate($this->toGenerate);
        
        
        return $node;
    }
    
    /**
    * Build a TableNode and append all children columnNodes to it
    * before passing TableNode to a parent NodeCollection
    *
    * @return \Faker\Components\Engine\DB\Builder\TableCollection
    * @access public
    */
    public function endTable()
    {
        return $this->end();
    }
    
    /**
    * Build a TableNode and append all children columnNodes to it
    * before passing TableNode to a parent NodeCollection
    *
    * @return \Faker\Components\Engine\DB\Builder\TableCollection
    * @access public
    */
    public function end()
    {
        $node     = $this->getNode();
        $children = $this->children();
        $parent   = $this->getParent();
        
        # add child columns to this TableNode        
        foreach($children as $child) {
            if(!$child instanceof ColumnNode) {
                throw new EngineException('TableBuilder has a non column node as a children and is not allowed');
            }
            
            $node->addChild($child);
        }
        
        # appent TableNode to parent builder
        $parent->append($node);
        
        return $parent;
        
    }
    
    
    /**
      *   Sets the number of rows to generate within this table
      *
      *   @access public
      *   @param integer $number the number of rows
      *   @return TableBuilder
      */
    public function toGenerate($number)
    {
        $this->toGenerate = $number;
        
        return $this;
    }
    
    /**
      *  Set the default locale on this table
      *
      *  @access public
      *  @param LocaleInterface $locale
      *  @return TableBuilder
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
      *  @return TableBuilder
      */
    public function defaultGenerator(GeneratorInterface $random)
    {
        $this->generator = $random;
        
        return $this;
    }
    
}
/* End of File */
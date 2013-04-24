<?php
namespace Faker\Components\Engine\DB\Builder;

use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Common\Builder\NodeCollection;
use Faker\Components\Engine\DB\Composite\TableNode;

/**
  *  Build a collection of TableNode
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class TableCollection extends NodeCollection
{
    
    
    /**
      *  Adds  a new table
      *
      * @access public
      * @param string the table database name
      * @return Faker\Components\Engine\DB\Builder\TableBuilder
      */
    public function addTable($name)
    {
        $builder = new TableBuilder($name,
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
        return null;
    }
    
    
    /**
    * Return the parent node and build the node
    * defined by this builder and append it to the parent.
    *
    * @return \Faker\Components\Engine\DB\Builder\SchemaBuilder 
    */
    public function end()
    {
        $children = $this->children();
        $parent   = $this->getParent();
        
        foreach($children as $child) {
            # this should not occur manly here as note to future developers
            if(!$child instanceof TableNode) {
                throw new EngineException('TableCollection has non table nodes as children not allowd behaviour');
            }
            $parent->append($child);
        }
        
        return $parent;
        
    }
    
    
    
}
/* End of File */
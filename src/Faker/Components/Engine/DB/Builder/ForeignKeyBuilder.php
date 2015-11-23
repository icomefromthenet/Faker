<?php
namespace Faker\Components\Engine\DB\Builder;

use Faker\Components\Engine\Common\Builder\AbstractDefinition;
use Faker\Components\Engine\DB\Composite\ForeignKeyNode;

/**
  *  Definition for the ForeignKey
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ForeignKeyBuilder extends AbstractDefinition
{
    
    /**
    * Instantiate and configure the node according to this definition
    *
    * @return Faker\Components\Engine\Common\Composite\CompositeInterface The node instance
    *
    * @throws InvalidDefinitionException When the definition is invalid
    */
    public function getNode()
    {
        $node = new ForeignKeyNode('ForeignKeyNode',$this->eventDispatcher);
                
        foreach($this->attributes as $attribute => $value) {
            $node->setOption($attribute,$value);
        }
        
        return $node;
    }
    
    /**
     *  Set the name of the foreignTable
     *
     *  @access public
     *  @return ForeignKeyBuilder
     *  @param string $name the foreign table name
     *
    */
    public function foreignTable($name)
    {
        $this->attribute('foreignTable',$name);
        return $this;
    }
    
    /**
     *  Set the name of the foreignColumn
     *
     *  @access public
     *  @return ForeignKeyBuilder
     *  @param string $name the foreign column name
     *
    */
    public function foreignColumn($name)
    {
        $this->attribute('foreignColumn',$name);
        return $this;
    }
    
    /**
     *  Turn off cache but keep relation checks and ordering
     *
     *  @access public
     *  @return ForeignKeyBuilder
     *  @param boolean $bool default to true
     *
    */
    public function silent($bool = true)
    {
        $this->attribute('silent',(boolean) $bool);
        return $this;
    }
    
    
    public function endForeignField()
    {
        return $this->end();
    }
}
/* End of File */
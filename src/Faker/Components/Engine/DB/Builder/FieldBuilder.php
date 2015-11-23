<?php
namespace Faker\Components\Engine\DB\Builder;

use Faker\Components\Engine\Common\Builder\NodeBuilder;
use Faker\Components\Engine\Common\Builder\FieldListInterface;
use Faker\Components\Engine\Common\Builder\SelectorListInterface;

/**
  *  Build a TypeNode as child to ColumnNode 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class FieldBuilder extends NodeBuilder implements FieldListInterface, SelectorListInterface
{
    
    
    /**
    * Build a TypeNode. 
    *
    * @return \Faker\Components\Engine\DB\Builder\ColumnBuilder
    * @access public
    */
    public function end()
    {
        return parent::end();
    }
    
    /**
    * Build a TypeNode. 
    *
    * @return \Faker\Components\Engine\DB\Builder\ColumnBuilder
    * @access public
    */
    public function endField()
    {
        return $this->end();
    }
}
/* End of File */
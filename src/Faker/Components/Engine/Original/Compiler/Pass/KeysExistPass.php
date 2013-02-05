<?php
namespace Faker\Components\Engine\Original\Compiler\Pass;

use Faker\Components\Engine\Original\Composite\CompositeInterface,
    Faker\Components\Engine\Original\Compiler\CompilerInterface,
    Faker\Components\Engine\Original\Exception as FakerException,
    Faker\Components\Engine\Original\Compiler\CompilerPassInterface,
    Faker\Components\Engine\Original\Visitor\MapBuilderVisitor,
    Faker\Components\Engine\Original\Visitor\Relationships,
    Faker\Components\Engine\Original\Visitor\RefCheckVisitor;

/*
 * class KeysExistPass
 */
class KeysExistPass implements CompilerPassInterface
{
    
    
     /**
      *  Check that all references used by Foreign Keys actually exist
      *
      *  @access public
      *  @param CompositeInterface $compiler
      *  @param CompilerInterface  $cmp
      */
    public function process(CompositeInterface $composite,CompilerInterface $cmp)
    {
        
        # use map visitor and gather our maps
        $map_visitor = new MapBuilderVisitor(new Relationships());
        $composite->acceptVisitor($map_visitor);
        $relation_map = $map_visitor->getMap();
        
        # check that every primary relation exists
        foreach($relation_map as $relationship) {
            
            $exists = new RefCheckVisitor($relationship->getForeign()->getTable(),$relationship->getForeign()->getColumn());
            $composite->acceptVisitor($exists);
            
            # foreign tables in this context ref to table/column where the key was generated and cached.
            if($exists->getFoundColumn() === null) {
                throw new FakerException($relationship->getForeign()->getTable() .'.'.$relationship->getForeign()->getColumn(). ' not found in composite');
            }
        }
    }

}
/* End of File */
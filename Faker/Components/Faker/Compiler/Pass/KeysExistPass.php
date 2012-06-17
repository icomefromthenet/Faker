<?php
namespace Faker\Components\Faker\Compiler\Pass;

use Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Compilder\CompilerPassInterface,
    Faker\Components\Faker\Visitor\MapBuilderVisitor,
    Faker\Components\Faker\Visitor\Relationships,
    Faker\Components\Faker\Visitor\RefCheckVisitor;

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
      */
    public function process(CompositeInterface $composite)
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
                throw new FakerException($relationship->getForeign()->getTable() .'.'.$relationship->getForeign()->getColumn(), ' not found in composite');
            }
        }
    }

}
/* End of File */
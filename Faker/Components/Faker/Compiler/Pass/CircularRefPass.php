<?php

namespace Faker\Components\Faker\Compiler\Pass;

use Faker\Components\Faker\Compiler\CompilerPassInterface,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Visitor\Relationships,
    Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Visitor\MapBuilderVisitor;
    

/*
 * class CacheInjectorPass
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
class CircularRefPass implements CompilerPassInterface
{
    
    
    /**
      *  Will Check the relationships for Circular References 
      *
      *  The References should be valid as
      *  missing ones will not cause error, run KeysExistPass first
      *
      *  A circular reference means that b requires a and a requires b and impossible situation or
      *  a requires b and requires c but c requires a, a giant circle.
      *
      *  @param CompositeInterface $composite
      */
    public function process(CompositeInterface $composite)
    {
        # build the relationship map        
        $map_visitor    = new MapBuilderVisitor(new Relationships());
        $composite->acceptVisitor($map_visitor);
        $map = $map_visitor->getMap();
        
        
        # make sure table have no bi-directional dependecies (b requires a and a requires b) 
        $tables_require_key = $map->getLocalRelations();
        
        foreach($tables_require_key as $relation) {
          
            # fetch tables that provide keys to this local table
            $tables_that_provide_keys = $map->filterByForeignTable($relation->getTable());
            
            # Check that above tables have no foreign keys that point back to the table defined in the current relation.
            $remaining = array_filter($tables_that_provide_keys,
                                      function($element) use ($relation){
                                        if($element->getForeign()->getTable() === $relation->getTable()) {
                                          return true;
                                        }
                                        
                                        return false;
                                    });
            
            if(count($remaining) > 0) {
                # throw an exception
                throw new FakerException(sprintf('Is a Bad Dependency between Relationships %s and %s',$relation->getTable(),$remaining[0]->getLocal()->getTable()));
            }
            
        }
        
        # make sre tables has no outter dependecies (a requires b and requires c but c requires a)
        
    }
    
    
}
/* End of File */
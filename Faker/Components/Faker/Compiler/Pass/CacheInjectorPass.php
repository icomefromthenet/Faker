<?php

namespace Faker\Components\Faker\Compiler\Pass;

use Faker\Components\Faker\CompilerPassInterface,
    Faker\Components\Faker\Composite\CompositeInterface,
    Faker\Components\Faker\Visitor\Relationships,
    Faker\Components\Faker\GeneratorCache,
    Faker\Components\Faker\Visitor\MapBuilderVisitor,
    Faker\Components\Faker\Visitor\ForeignCacheInjectorVisitor,
    Faker\Components\Faker\Visitor\ColumnCacheInjectorVisitor;

/*
 * class CacheInjectorPass
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */
class CacheInjectorPass implements CompilerPassInterface
{
    
    
    /**
      *  Will inject Cache into the composite
      *
      *  The References should be valid as
      *  missing ones will not cause error, run KeysExistPass first
      *
      *  @param CompositeInterface $composite
      */
    public function process(CompositeInterface $composite)
    {
        # build the relationship map        
        $map_visitor    = new MapBuilderVisitor(new Relationships());
        $composite->acceptVisitor($map_visitor);
        $map = $map_visitor->getMap();
        
        foreach($map as $relationship) {
            $cache          = new GeneratorCache();

            # inject cache into foreign reference (where key originates)
            $foreign_cache_injector = new ForeignCacheInjectorVisitor(
                                                                      $cache,
                                                                      $relationship->getForeign()->getTable(),
                                                                      $relationship->getForeign()->getColumn()
                                                                      );
            $composite->acceptVisitor($foreign_cache_injector);
            
            # inject same cache into the local container.
            # container is used to allow multiple containers per column for composite keys.
            # not good design to combine keys into single column but still done on occasion.
            $local_cache_injector   = new ColumnCacheInjectorVisitor(
                                                                     $cache,
                                                                     $relationship->getLocal()->getTable(),
                                                                     $relationship->getLocal()->getColumn(),
                                                                     $relationship->getLocal()->getContainer()
                                                                     );
            $composite->acceptVisitor($local_cache_injector);
            
        }
        
    }
    
    
}
/* End of File */
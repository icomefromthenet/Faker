<?php
namespace Faker\Components\Engine\Original\Compiler\Pass;

use Faker\Components\Engine\Original\Compiler\CompilerPassInterface,
    Faker\Components\Engine\Original\Compiler\CompilerInterface,
    Faker\Components\Engine\Original\Composite\CompositeInterface,
    Faker\Components\Engine\Original\Composite\Column,
    Faker\Components\Engine\Original\Composite\ForeignKey,
    Faker\Components\Engine\Original\GeneratorCache,
    Faker\Components\Engine\Original\CacheInterface,
    Faker\Components\Engine\Original\Exception as FakerException;
    
    
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
      *  @param CompilerInterface  $cmp
      */
    public function process(CompositeInterface $composite,CompilerInterface $cmp)
    {
        # find nodes that are Columns
        foreach($cmp->getGraph()->getNodes() as $node) {
            $node_value = $node->getValue();
            
            if($node_value instanceof Column) {
                
                $inner_edges = $node->getInEdges();
                $f_key_found = false;
                $cache       = new GeneratorCache();
                
                #assign cache to foreign keys assocaited with this column.
                
                foreach($inner_edges as $inEdge) {
                    $composite_type = $inEdge->getSourceNode()->getValue();
                    
                    if($composite_type instanceof ForeignKey) {
                        # test if cache is wanted, might only exist to establish relationship.    
                        if($composite_type->getUseCache() === true) {
                            $composite_type->setGeneratorCache($cache);
                            $f_key_found = true;            
                        }
                    }
                }
                
                #assign the cache to the column.
                
                if($f_key_found) {
                    if(!$node_value instanceof CacheInterface) {
                            throw new FakerException('Column:: '. $node->getValue()->getId() .' does not implement CacheInterface');
                    }
                    $node_value->setUseCache(true)->setGeneratorCache($cache);
                }
                
            }
        }
        
    }
}
/* End of File */
<?php
namespace Faker\Components\Engine\Common\Compiler\Pass;

use Faker\Components\Engine\Common\GeneratorCache;
use Faker\Components\Engine\Common\Compiler\CompilerPassInterface;
use Faker\Components\Engine\Common\Compiler\CompilerInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Composite\ColumnNode;
use Faker\Components\Engine\Common\Composite\ForeignKeyNode;
use Faker\Components\Engine\Common\Composite\GeneratorInterface;

    
/*
 * CacheInjectorPass Inject the column cache where foreign keys found
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
            $compositeNode = $node->getValue();
            
            if($compositeNode instanceof ColumnNode) {
                
                $innerEdges = $node->getInEdges();
                $cache       = new GeneratorCache(); //once cache per column
                
                #assign cache to foreign keys assocaited with this column.
                foreach($innerEdges as $inEdge) {
                    
                    $compositeType = $inEdge->getSourceNode()->getValue();
                    
                    if($compositeType instanceof ForeignKeyNode) {

                        $compositeType->setResultCache($cache);
                        
                        # assign cache to column that assigned to the FK
                        # do it here as don't know if a column has foreignkeys
                        # until matched.
                        if($compositeNode->getResultCache() === null ) {
                            $compositeNode->setResultCache($cache);
                        }
                        
                    }
                }
                
            }
        }
        
    }
}
/* End of File */
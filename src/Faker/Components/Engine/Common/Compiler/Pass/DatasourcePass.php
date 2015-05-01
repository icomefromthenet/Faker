<?php
namespace Faker\Components\Engine\Common\Compiler\Pass;

use Faker\Components\Engine\Common\Compiler\CompilerPassInterface;
use Faker\Components\Engine\Common\Compiler\CompilerInterface;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\Common\Visitor\DSourceInjectorVisitor;

    
/*
 * Execute the datasource injector visitor, this will map Datasource Composite Nodes into
 * the dependent Table/Types Composite Nodes where a FromSource Type Exists in a TypeNode.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.5
 */
class DatasourcePass implements CompilerPassInterface
{
    
    /**
     * @var Faker\Components\Engine\Common\Visitor\DSourceInjectorVisitor
     */ 
    protected $visitor;
    
    public function __construct(DSourceInjectorVisitor $visitor)
    {
        $this->visitor = $visitor;
    }
    
    
    /**
      *   This will map DatasourceNodes into the dependent Table/Types Composite Nodes
      *
      *  @param CompositeInterface $composite
      *  @param CompilerInterface  $cmp
      */
    public function process(CompositeInterface $composite,CompilerInterface $cmp)
    {
        $composite->acceptVisitor($this->getSourceVisitor());
    }
    
    /**
     * Return the assigned visitor
     * 
     * @return Faker\Components\Engine\Common\Visitor\DSourceInjectorVisitor
     * @access public
     */ 
    public function getSourceVisitor()
    {
        return $this->visitor;
    }
}
/* End of File */
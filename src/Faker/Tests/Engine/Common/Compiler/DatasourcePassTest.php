<?php
namespace Faker\Tests\Engine\Common\Compiler;

use Faker\Components\Engine\Common\Compiler\Pass\DatasourcePass;
use Faker\Components\Engine\Common\Compiler\Graph\DirectedGraph;
use Faker\Components\Engine\Common\Visitor\DSourceInjectorVisitor;
use Faker\Components\Engine\Common\Composite\PathBuilder;
use Faker\Components\Engine\Common\Composite\CompositeInterface;
use Faker\Components\Engine\DB\Composite\SchemaNode;
use Faker\Components\Engine\DB\Composite\TableNode;
use Faker\Components\Engine\DB\Composite\ColumnNode;
use Faker\Components\Engine\DB\Composite\ForeignKeyNode;
use Faker\Tests\Base\AbstractProject;


class DatasourcePassTest extends AbstractProject
{

    
    public function testPassExecutesVisitor()
    {
        # assert schema is passed visitor
        $schema = $this->getMockBuilder('Faker\\Components\\Engine\\DB\\Composite\\SchemaNode')
             ->disableOriginalConstructor()
             ->setMethods(array('acceptVisitor'))
             ->getMock();
             
        $compiler = $this->getMock('Faker\\Components\\Engine\\Common\\Compiler\\CompilerInterface');
        
        $path      = new PathBuilder();
        
        $visitor = new DSourceInjectorVisitor($path);
        $pass    = new DatasourcePass($visitor);
        
        # assert visitor property 
        $this->assertEquals($visitor,$pass->getSourceVisitor());
        
        # assert the visitor is used
        $schema->expects($this->once())  
               ->method('acceptVisitor')
               ->with($this->equalTo($visitor));
             
        $pass->process($schema,$compiler);  
    }
    
        
   
}
/* End of File */
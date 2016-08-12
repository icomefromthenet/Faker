<?php
namespace Faker\Tests\Engine\Common\Datasource;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\Common\Datasource\PHPSourceDefinition;
use Faker\Components\Engine\Common\Datasource\PHPDatasource;
use Faker\Components\Engine\EngineException;

class PHPSourceDefinitionTest extends AbstractProject
{
    
    
    public function testSourceAssigned()
    {
        $iterator = new \ArrayIterator();
        $iterator->append(array('value'=> 1));
        $iterator->append(array('value'=> 2));
        $iterator->append(array('value'=> 3));
        $iterator->append(array('value'=> 4));
        $iterator->append(array('value'=> 5));
        $iterator->append(array('value'=> 6));
        
        $def = new PHPSourceDefinition();
        
        $def->setDataIterator($iterator);
        
        $node = $def->getNode();
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Datasource\PHPDatasource',$node);
        $this->assertInstanceOf('\Iterator',$node->getIterator());
    }
    
    
    public function testSourceWithClosure()
    {
        $iteratorClosure = function() {
            $iterator = new \ArrayIterator();
            $iterator->append(array('value'=> 1));
            $iterator->append(array('value'=> 2));
            $iterator->append(array('value'=> 3));
            $iterator->append(array('value'=> 4));
            $iterator->append(array('value'=> 5));
            $iterator->append(array('value'=> 6));
            
            return $iterator;
        };
        
        
       $def =  new PHPSourceDefinition();
       
       $def->setDataFromClosure($iteratorClosure);
       
       $node = $def->getNode();
       $this->assertInstanceOf('Faker\Components\Engine\Common\Datasource\PHPDatasource',$node);
       $this->assertInstanceOf('\Iterator',$node->getIterator());
    }
    
     public function testSourceWithClosureAlias()
    {
        $iteratorClosure = function() {
            $iterator = new \ArrayIterator();
            $iterator->append(array('value'=> 1));
            $iterator->append(array('value'=> 2));
            $iterator->append(array('value'=> 3));
            $iterator->append(array('value'=> 4));
            $iterator->append(array('value'=> 5));
            $iterator->append(array('value'=> 6));
            
            return $iterator;
        };
        
        
       $def =  new PHPSourceDefinition();
       
       $def->setDataClosure($iteratorClosure);
       
       $node = $def->getNode();
       $this->assertInstanceOf('Faker\Components\Engine\Common\Datasource\PHPDatasource',$node);
       $this->assertInstanceOf('\Iterator',$node->getIterator());
       
    }
    
}
/* End of File */
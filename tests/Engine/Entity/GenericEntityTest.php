<?php
namespace Faker\Tests\Engine\Entity;

use Faker\Components\Engine\Entity\GenericEntity;
use Faker\Tests\Base\AbstractProject;

class GenericEntityTest extends AbstractProject
{
    
    public function testEntity()
    {
        $result = array('field1' => 'a', 'field2' => false);
        $entity = new GenericEntity($result);
        
        
        $this->assertEquals($entity->field1,'a');
        $this->assertEquals($entity->field2,false);
        
    }
    
    /**
      *  @expectedException Faker\Components\Engine\EngineException
      *  @expectedExceptionMessage The field field2 has not been set for this entity yet
      */
    public function testEntityFieldNotSet()
    {
        $result = array('field1' => 'a');
        $entity = new GenericEntity($result);
        
        $entity->field2;
        
        
    }
    
    public function testEmptyFieldCanBeAssigned()
    {
        $result = array('field1' => 'a');
        $entity = new GenericEntity($result);
        
        $entity->field2 = 'no value';
        $this->assertEquals($entity->field2,'no value');
        
    }
    
}
/*End of file */

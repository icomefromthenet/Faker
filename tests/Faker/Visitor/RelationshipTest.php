<?php
namespace Faker\Tests\Faker\Visitor;

use Faker\Components\Faker\Visitor\Relationships,
    Faker\Components\Faker\Visitor\Relationship,
    Faker\Components\Faker\Visitor\Relation,
    Faker\Tests\Base\AbstractProject;

class RelationshipTest extends AbstractProject
{

    public function testRelation()
    {
        $relation = new Relation('table1','columnA','none');
    
        $this->assertEquals('table1',$relation->getTable());
        $this->assertEquals('columnA',$relation->getColumn());
        $this->assertEquals('none',$relation->getContainer());
    
        # test relation with empty container
        
        $relation = new Relation('table1','columnA');
    
        $this->assertEquals('table1',$relation->getTable());
        $this->assertEquals('columnA',$relation->getColumn());
        $this->assertEquals(null,$relation->getContainer());
    
    
    }
    
    public function testRelationship()
    {
        $local   = new Relation('table1','columnA','table1.columnA');
        $foreign = new Relation('table2','columnA');
        
        $relationship = new Relationship($local,$foreign);
        
        $this->assertEquals($local,$relationship->getLocal());
        $this->assertEquals($foreign,$relationship->getForeign());
        
    }
    
    public function testRelationships()
    {
        $local = new Relation('table1','columnA','table1.columnA');
        $foreign = new Relation('table2','columnA');
        
        $relationship = new Relationship($local,$foreign);
        
        $relationship_collection = new Relationships();
        $relationship_collection->add($relationship);
        
        # test Count Interface
        $this->assertEquals(1,count($relationship_collection));
        
        # test IteratorAggregate Interface
        $this->assertInstanceOf('\ArrayIterator',$relationship_collection->getIterator());
        $this->assertEquals(1,iterator_count($relationship_collection->getIterator()));
    
    
        # test getForeignRelations
        $foreign_relations = $relationship_collection->getForeignRelations();
        $this->assertEquals(1,count($foreign_relations));
        $this->assertInstanceOf('\Faker\Components\Faker\Visitor\Relation',$foreign_relations[0]);
        $this->assertEquals('table2',$foreign_relations[0]->getTable());
        $this->assertEquals('columnA',$foreign_relations[0]->getColumn());
        $this->assertEquals(null,$foreign_relations[0]->getContainer());
        
        # test getLocalRelations
        $local_relations = $relationship_collection->getLocalRelations();
        $this->assertEquals(1,count($local_relations));
        $this->assertInstanceOf('\Faker\Components\Faker\Visitor\Relation',$local_relations[0]);
        $this->assertEquals('table1',$local_relations[0]->getTable());
        $this->assertEquals('columnA',$local_relations[0]->getColumn());
        $this->assertEquals('table1.columnA',$local_relations[0]->getContainer());
        
    }
    
    
    public function testGetRelationsByTable()
    {
        $localA   = new Relation('table1','columnA','table1.columnA');
        $foreignA = new Relation('table2','columnA');
        
        $localB   = new Relation('table2','columnA','table1.columnA');
        $foreignB = new Relation('table1','columnA');
        
        
        $relationshipA = new Relationship($localA,$foreignA);
        $relationshipB = new Relationship($localB,$foreignB);
        
        $relationship_collection = new Relationships();
        $relationship_collection->add($relationshipA);
        $relationship_collection->add($relationshipB);
        
        
        $foreign = $relationship_collection->getForeignRelationsByTable('table2');
        $this->assertEquals(1,count($foreign));
        $this->assertEquals('table2',$foreign[0]->getTable());
        $this->assertEquals('columnA',$foreign[0]->getColumn());        
        
        
        $local = $relationship_collection->getLocalRelationsByTable('table1');
        $this->assertEquals(1,count($local));
        $this->assertEquals('table1',$local[0]->getTable());
        $this->assertEquals('columnA',$local[0]->getColumn());
        $this->assertEquals('table1.columnA',$local[0]->getContainer());
        
        $local_other = $relationship_collection->getLocalRelationsByTable('table2');
        $this->assertEquals(1,count($local_other));
        
        $foreign_other = $relationship_collection->getForeignRelationsByTable('table2');
        $this->assertEquals(1,count($foreign_other));
    }
    
}
/* End of File */
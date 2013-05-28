<?php
namespace Faker\Tests\Engine\XML\Composite;

use Faker\Tests\Base\AbstractProject;
use Faker\Components\Engine\XML\Builder\NodeBuilder;
use Faker\Components\Engine\XML\Composite\SchemaNode;
use Faker\Components\Engine\Common\Formatter\Phpunit;
use Faker\Components\Engine\Common\Formatter\Sql;

class BuilderTest extends AbstractProject
{
    
    
    public function testBuilderDIBuilder()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $this->assertInstanceOf('Faker\Components\Engine\XML\Builder\NodeBuilder',$builder);
        
    }
    
    
    public function testBuilderAddSchema()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $builder->addSchema('myschema');
        
        $this->assertInstanceOf('Faker\Components\Engine\XML\Composite\SchemaNode',$builder->getSchema());
        $this->assertInstanceOf('Faker\Components\Engine\XML\Composite\SchemaNode',$builder->getHead());
        $this->assertEquals('myschema',$builder->getSchema()->getId());
        $this->assertEquals('en',$builder->getSchema()->getOption('locale','en'));
        
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage Scheam already added only have one
    */
    public function testBuilderOneSchemaOnly()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $builder->addSchema('myschema');
        $builder->addSchema('newschema');
    }
    
    
    public function testBuilderSchemaAcceptsOptions()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $locale = 'uk';
        $builder->addSchema('myschema',array('locale'=>$locale));
        
        $this->assertInstanceOf('Faker\Components\Engine\XML\Composite\SchemaNode',$builder->getSchema());
        $this->assertEquals($locale,$builder->getSchema()->getOption('locale'));
        
    }
    
    /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage Must have set a schema before adding writter
    */
    public function testBuilderAddWritterFailsNoSchema()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $writer  = $builder->addWriter('mysql','phpunit');
        
    }
    
    public function testBuilderAddWritter()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $builder->addSchema('myschema',array());
        $writer  = $builder->addWriter('mysql','phpunit'); 
        
        $schemaChildren = $builder->getHead()->getChildren();
        $this->assertInstanceOf('Faker\Components\Engine\XML\Composite\FormatterNode',$schemaChildren[0]);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Formatter\Phpunit',$schemaChildren[0]->getInternal());
    }
    
     /**
     * @expectedException Faker\Components\Engine\EngineException
     * @expectedExceptionMessage Formatter does not exist at::baaaa
    */
    public function testBuilderAddWritterNotExist()
    {
        $builder = $this->getProject()->getXMLEngineBuilder();
        $builder->addSchema('myschema',array());
        $writer  = $builder->addWriter('mysql','baaaa'); 
 
        
    }
    
    
    public function testBuilderAddTable()
    {
        
        
    }
    
    public function testBuilderAddTableOnlySchemaHead()
    {
        
    }
    
    
}
/* End of File */
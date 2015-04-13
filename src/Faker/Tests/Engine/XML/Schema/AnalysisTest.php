<?php
namespace Faker\Tests\Engine\XML\Schema;

use Faker\Components\Engine\XML\Parser\SchemaAnalysis;
use Faker\Tests\Base\AbstractProjectWithDb;


class AnalysisTest extends AbstractProjectWithDb
{
    
    public function __construct()
    {
        # build out test database
        $this->buildDb();
        
        parent::__construct();
    }


    public function testAnalyse()
    {
        $project  = $this->getProject();
        $database = $this->getDoctrineConnection();
        
        
        $builder  = $project->getXMLEngineBuilder();
        $analysis = new SchemaAnalysis();
        
        $composite = $analysis->analyse($database,$builder);
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\GeneratorInterface',$composite);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Composite\SerializationInterface',$composite);
        
        # last child is a formatter
        $schemaChildren = $composite->getChildren();
        $this->assertInstanceOf('Faker\Components\Engine\XML\Composite\FormatterNode',$schemaChildren[10]);
        
        # that have more than 2 nodes 1 formatter an x > 1 table
        $this->assertGreaterThan(2,count($schemaChildren));
        
        # xml seralize outputs closing schema tag
        $this->assertContains('</schema>',$composite->toXml());
    }

}
/* End of File */

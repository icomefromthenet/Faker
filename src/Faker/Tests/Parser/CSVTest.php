<?php
namespace Faker\Tests\Parser;

use Faker\Parser\File,
    Faker\Parser\VFile,
    Faker\Parser\FileFactory,
    Faker\Parser\Parser\CSV as CSVParser,
    Faker\Parser\Analysis\CSV as CSVAnalysis,
    Faker\Parser\ParseOptions,
    Symfony\Component\EventDispatcher\EventDispatcher,
    Faker\Tests\Base\AbstractProject;

class CSVTest extends AbstractProject
{
    
    protected $str;

    public function setUp()
    {
          $this->str = <<<EOF
Year,Make,Model,Length
1997,Ford,E350,2.34
2000,Mercury,Cougar,2.38
EOF;

     file_put_contents('example.csv',$this->str);

      parent::setUp();
    }


    public function tearDown()
    {
        parent::tearDown();
        
        if(file_exists('example.csv')) {
            unlink('example.csv');
        }
    }
    
    
    public function testAnalysisImplementsInterface()
    {
        $event = new EventDispatcher();
        $file  = new File('example.csv');
        $options = new ParseOptions();
        $analysis = new CSVAnalysis($event);
        
        $this->assertInstanceOf('\\Faker\\Parser\\AnalysisInterface',$analysis);
        
        //$analysis->analyse($file, $options);
    }
    
    
    
    public function testCSVParser()
    {
        
        $event = new EventDispatcher();
        $file  = new File('example.csv');
        $options = new ParseOptions();
        $parser = new CSVParser($event);
        
        $this->assertInstanceOf('\\Faker\\Parser\\ParserInterface',$parser);
        
        //$parser->parse($file,$options);
        
    }
    
    
    
}
/* End of File */
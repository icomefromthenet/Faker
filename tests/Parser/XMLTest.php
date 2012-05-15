<?php
namespace Faker\Tests\Parser; 

use Faker\Parser\File,
    Faker\Parser\VFile,
    Faker\Parser\FileFactory,
    Faker\Parser\Parser\XML as XMLParser,
    Faker\Parser\Analysis\XML as XMLAnalysis,
    Faker\Parser\ParseOptions,
    Symfony\Component\EventDispatcher\EventDispatcher,
    Faker\Tests\Base\AbstractProject;
    
class XMLTest extends AbstractProject
{
    
    protected $str;

    public function setUp()
    {
          $this->str = <<<EOF
<?xml version="1.0" encoding="ISO-8859-1"?>
    <note name="alpha">
       <to>Tove</to>
       <from>Jani</from>
       <heading>
        <inner>
            innerval
        </inner>
       </heading>
       <body>Don't forget me this weekend!</body>
   </note>
EOF;

     file_put_contents('example.xml',$this->str);

      parent::setUp();
    }


    public function tearDown()
    {
        parent::tearDown();
        
        if(file_exists('example.xml')) {
            unlink('example.xml');
        }
    }
    
    
    public function testAnalysisImplementsInterface()
    {
        $event = new EventDispatcher();
        $file  = new File('example.xml');
        $options = new ParseOptions();
        $analysis = new XMLAnalysis($event);
        
        $this->assertInstanceOf('\\Faker\\Parser\\AnalysisInterface',$analysis);
        
        //$analysis->analyse($file, $options);
    }
    
    
    
    public function testXMLRegisterParser()
    {
        
        $event = new EventDispatcher();
        $file  = new File('example.xml');
        $options = new ParseOptions();
        $parser = new XMLParser($event);
        
        $this->assertInstanceOf('\\Faker\\Parser\\ParserInterface',$parser);
        
        $resource = $parser->register();
        
        # test the property and returned parser are same
        $this->assertSame($resource,$parser->getParser());
        
        # test that set a parser option
        $this->assertTrue($parser->setOption(XML_OPTION_CASE_FOLDING,true));
        $this->assertEquals($parser->getOption(XML_OPTION_CASE_FOLDING),true);
        
        # test that unregister
        
        $this->assertTrue($parser->unregister());
        $this->assertSame($parser->getParser(),null);
        
    }
    
    /**
      *  @expectedException \Faker\Parser\Exception
      *  @ExpectedExceptionMessage Parser not been registered
      */
    public function testXMLParseNotRegistered()
    {
        
        $event = new EventDispatcher();
        $file  = new File('example.xml');
        $options = new ParseOptions();
        $parser = new XMLParser($event);
        
        $parser->parse($file,$options);
        
        $this->assertTrue(true);
    }
    
    public function testXMLParse()
    {
        
        $event = new EventDispatcher();
        $file  = new File();
        $file->fopen('example.xml');
        $options = new ParseOptions();
        $parser = new XMLParser($event);
        
        $parser->register();
        $parser->parse($file,$options);
        
        $this->assertTrue(true);
    }
    
}
/* End of File */
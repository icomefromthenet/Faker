<?php
namespace Faker\Tests\Parser; 

use Faker\Parser\File,
    Faker\Parser\VFile,
    Faker\Parser\FileFactory,
    Faker\Parser\ParseOptions,
    Faker\Tests\Base\AbstractProject;


class ParseOptionsTest extends AbstractProject
{
    
    public function testProperties()
    {
        $options = new ParseOptions();
        $this->assertInstanceOf('\\Faker\\Parser\\ParseOptions',$options);
        
        
         # test parser property
         $parser = 'csv';
         $options->setParser($parser);
         $this->assertSame($parser,$options->getParser());
         
         # test field seperator
         $field = ord(',');
         $options->setFieldSeperator($field);
         $this->assertSame(',',$options->getFieldSeperator());
             
         # test deliminator
         $deliminator = ord(',');
         $options->setDeliminator($deliminator);
         $this->assertSame(',',$options->getDeliminator());
         
         # test skip rows
         $skip = 0;
         $options->setSkipRows($skip);
         $this->assertSame($skip,$options->getSkipRows());
         
         # test header rows
         $header = true;
         $options->setHasHeaderRow($header);
         $this->assertSame($header,$options->getHasHeaderRow());
         
         # test eol ignore
         $ignore_eol_chr = '/n'; 
         $options->setEolIgnoreChr($ignore_eol_chr);
         $this->assertSame($ignore_eol_chr,$options->getEolIgnoreChr());
        
    }
    
    
    
    
    
}
/* End of File */
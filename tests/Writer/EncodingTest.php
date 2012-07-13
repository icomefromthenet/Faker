<?php
namespace Faker\Tests\Writer;

use Faker\Project,
    Faker\Components\Writer\Encoding,
    Faker\Tests\Base\AbstractProject;

class EncodingTest extends AbstractProject
{
    
    public function testValidateEncoding()
    {
        $encoder = new Encoding('UTF-8','UTF-8');
        
        $this->assertFalse($encoder->validateEncoding('aaa'));
        $this->assertTrue($encoder->validateEncoding('ASCII'));
        
    }
    
    /**
      *  @expectedException \Faker\Components\Writer\Exception
      *  @expectedExceptionMessage writer::__construct in encoding aaa is invalid
      */
    public function testEncodingBadIn()
    {
        $encoder = new Encoding('aaa','UTF-8');
        
    }
    
    
    /**
      *  @expectedException \Faker\Components\Writer\Exception
      *  @expectedExceptionMessage writer::__construct out encoding aaa is invalid
      */
    public function testEncodingBadOut()
    {
        $encoder = new Encoding('UTF-8','aaa');
        
    }
    
    
    public function testEncoding()
    {
        $encoder = new Encoding('ASCII','UTF-8');
        
        $out = $encoder->encode('my test ascii string');
        
        $this->assertTrue(mb_check_encoding($out,'UTF-8'));
        
    }
    
    
    public function testEncodingPropertites()
    {
        $encoder = new Encoding('UTF-8','UTF-8');
        
        $out = $encoder->setInEncoding('ascii')->encode('my test ascii string');
        
        $this->assertTrue(mb_check_encoding($out,'UTF-8'));
        
        $encoder = new Encoding('UTF-8','ASCII');
        
        $out = $encoder->setOutEncoding('ascii')->encode('my test ascii string');
        
        $this->assertTrue(mb_check_encoding($out,'ASCII'));
        
    }
    
}
/* End of File */
<?php
namespace Faker\Tests\Text;

use Faker\Tests\Base\AbstractProject,
    Faker\Text\MbProvider,
    Faker\Text\SimpleString,
    Faker\Text\StringIterator;

class StringIteratorTest extends AbstractProject
{
    
    
    public function testNewIterator()
    {
        $str = 'this is a fixed length string';
        $str_split = str_split($str);
        $simpleString = new SimpleString(new MbProvider($str));
        
        $iterator = new StringIterator($simpleString);
        $this->assertInstanceOf('Faker\Text\StringIterator',$iterator);        
    
        foreach($iterator as $index => $char) {
            $this->assertEquals($char,$str_split[$index]);
        }
    
    }
    
}
/* End of File */
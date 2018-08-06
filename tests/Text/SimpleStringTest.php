<?php
namespace Faker\Tests\Text;

use Faker\Tests\Base\AbstractProject,
    Faker\Text\SimpleString;

class SimpleStringTest extends AbstractProject
{
    
    public function testStringFactory()
    {
        $string = SimpleString::create('a string is simple');
        
        $this->assertInstanceOf('\Faker\Text\SimpleString',$string);
        $this->assertInstanceOf('\Faker\Text\SimpleStringInterface',$string);
        $this->assertInstanceOf('\Faker\Text\StringFactoryInterface',$string);
        
        $this->assertEquals('a string is simple',(string)$string);
        
    }
    
    public function testStringFactoryProviderIncluded()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('__toString')
                ->will($this->returnValue('another string'));
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertEquals('another string',(string)$string);
        
    }
    
    
    public function testAppend()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('append')
                ->with($this->equalTo('another string'));
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->append('another string'));
    }
    
    public function testPrepend()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('prepend')
                ->with($this->equalTo('another string'));
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->prepend('another string'));
        
    }
    
    public function testChop()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('chop');
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->chop());
        
    }
    
    public function testCut()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('cut')
                ->with($this->equalTo('is'),$this->equalTo(false));
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->cut('is',false));
        
    }

    public function testShorten()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('shorten')
                ->with($this->equalTo(5));
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->shorten(5));
    }
    
    
    public function testReverse()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('reverse');
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->reverse());
    }
    
    
    public function testScramble()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('scramble');
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->scramble());
    }
    
    
    public function testShuffle()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('shuffle');
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->shuffle());
        
    }
    
    public function testSeo()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('seo')
                ->with($this->equalTo('ll'));
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->seo('ll'));
        
    }
    
    public function testEmphasize()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('emphasize')
                ->with($this->equalTo('string'),$this->equalTo('u'));
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->emphasize('string','u'));
        
    }
    
    public function testCensor()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('censor')
                ->with($this->equalTo('fuck'));
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->censor('fuck'));
    }
    
    public function testLowerCase()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('toLowerCase');
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->toLowerCase());
        
    }
    
    public function testUpperCase()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('toUpperCase');
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->toUpperCase());
        
    }
    
    public function testSentenceCase()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('toSentenceCase');
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->toSentenceCase());
    }
    
    public function testToTitle()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('toTitleCase');
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->toTitleCase());
        
    }
    
    public function testToUnderscores()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('toUnderscores');
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->toUnderscores());
    }
    
    public function testToCamelCase()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('toCamelCase');
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->toCamelCase());
    }
    
    public function testRemoveNonAlpha()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('removeNonAlpha');
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->removeNonAlpha());
        
    }
    
    public function testRemoveNonAlphanumeric()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('removeNonAlphanumeric');
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->removeNonAlphanumeric());
        
    }
    
    public function testRemoveNonNumeric()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('removeNonNumeric');
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->removeNonNumeric());
    }
    
    public function testRemoveDuplicates()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('removeDuplicates');
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->removeDuplicates());
        
    }
    
    public function testRemoveDelimiters()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('removeDelimiters');
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->removeDelimiters());
        
    }
    
    public function testTrim()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('trim');
                
        
        $string   = SimpleString::create('a string is simple',$provider);
        
        $this->assertSame($string,$string->trim());
    }
    
    public function testRTrim()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('rtrim');
        
        $string   = SimpleString::create('a string is simple',$provider);
        $this->assertSame($string,$string->rtrim());
        
    }

    public function testUcfirst()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('ucfirst');
        
        $string   = SimpleString::create('a string is simple',$provider);
        $this->assertSame($string,$string->ucfirst());
    }
    
    
    public function testLcFrist()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('lcfirst');
        
        $string   = SimpleString::create('a string is simple',$provider);
        $this->assertSame($string,$string->lcfirst());
        
    }
    
    public function testRepeat()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('repeat')
                ->with($this->equalTo(3));
        
        $string   = SimpleString::create('a string is simple',$provider);
        $this->assertSame($string,$string->repeat(3));
    }
    
    public function testCaseSensitive()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('caseSensitive');
        
        $string   = SimpleString::create('a string is simple',$provider);
        $this->assertSame($string,$string->caseSensitive());
    }
    
    
    public function testCaseInsensitive()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('caseInsensitive');
        
        $string   = SimpleString::create('a string is simple',$provider);
        $this->assertSame($string,$string->caseInsensitive());
        
    }
    
    
    public function testConvertEncoding()
    {
        
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('convertEncoding')
                ->with($this->equalTo('UTF-16'));
        
        $string   = SimpleString::create('a string is simple',$provider);
        $this->assertSame($string,$string->convertEncoding('UTF-16'));
        
    }
    
    public function testClear()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('clear');
        
        $string   = SimpleString::create('a string is simple',$provider);
        $this->assertSame($string,$string->clear());
    }
    
    public function testRegexReplace()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('regexReplace')
                ->with($this->equalTo('[a-zA-Z]'),$this->equalTo('aaaaa'));
        
        $string   = SimpleString::create('a string is simple',$provider);
        $this->assertSame($string,$string->regexReplace('[a-zA-Z]','aaaaa'));
    }
    
    public function testLastPosition()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('lastPosition')
                ->with($this->equalTo('needle'),$this->equalTo(0))
                ->will($this->returnValue(false));
        
        $string   = SimpleString::create('a string is simple',$provider);
        $this->assertFalse($string->lastPosition('needle',0));
    }
    
    public function testFirstPosition()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('firstPosition')
                ->with($this->equalTo('needle'),$this->equalTo(0))
                ->will($this->returnValue(false));
        
        $string   = SimpleString::create('a string is simple',$provider);
        $this->assertFalse($string->firstPosition('needle',0));
        
    }
    
    public function testContains()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('contains')
                ->with($this->equalTo('needle'),$this->equalTo(0))
                ->will($this->returnValue(false));
        
        $string   = SimpleString::create('a string is simple',$provider);
        $this->assertFalse($string->contains('needle')); 
    }
    
    public function testLength()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('length')
                ->will($this->returnValue(1));
        
        $string   = SimpleString::create('a string is simple',$provider);
        $this->assertEquals(1,$string->length());    
    }
    
    
    public function testWords()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('words')
                ->will($this->returnSelf());
        
        $string   = SimpleString::create('a string is simple',$provider);
        $new = $string->words();
        $this->assertInstanceOf('Faker\Text\SimpleStringInterface',$new);
        $this->assertNotSame($string,$new);
    }
    
    public function testIntersect()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('intersect')
                ->with($this->equalTo('word1'))
                ->will($this->returnSelf());
        
        $string   = SimpleString::create('a string is simple',$provider);
        $new = $string->intersect('word1');
        $this->assertInstanceOf('Faker\Text\SimpleStringInterface',$new);
        $this->assertNotSame($string,$new);
    }
    
    
    public function testSplit()
    {
        $provider = $this->createMock('\Faker\Text\SimpleStringInterface');
        $provider->expects($this->once())
                ->method('split')
                ->with($this->equalTo('word1'))
                ->will($this->returnSelf());
        
        $string   = SimpleString::create('a string is simple',$provider);
        $new = $string->split('word1');
        $this->assertInstanceOf('Faker\Text\SimpleStringInterface',$new);
        $this->assertNotSame($string,$new);
    }
    
}
/* End of File */
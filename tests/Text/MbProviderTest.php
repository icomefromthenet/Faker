<?php
namespace Faker\Tests\Text;

use Faker\Tests\Base\AbstractProject,
    Faker\Text\MbProvider;
    
class MbProviderTest extends AbstractProject
{
    
    public function testAppend()
    {
        $string = new MbProvider('îpsum LêchugÆ Æmët');
        $string->append(" lÔrem");
        $this->assertEquals('îpsum LêchugÆ Æmët lÔrem', $string->__toString());
    }
    
    public function testPrepend()
    {
        $string = new MbProvider('îpsum LêchugÆ Æmët');
        $string->prepend("lÔrem ");
        $this->assertEquals('lÔrem îpsum LêchugÆ Æmët', $string->__toString());
    }
    
    public function testChop()
    {
        $string = new MbProvider('îpsum LêchugÆ Æmët');
        $string->chop();
        $this->assertEquals('îpsum LêchugÆ Æmë', $string->__toString());
    }
    
    public function testCutCaseSensitive()
    {
        # test for case senestive ok
        $string = new MbProvider('îpsum LêchugÆ Æmët');
        $string->caseSensitive()->cut('Æmët',true);
        $this->assertEquals('îpsum LêchugÆ ', $string->__toString());
    }
    
    
    public function testCutCaseInsensitive()
    {
        # test for case insenestice
        $string = new MbProvider('îpsum LêchugÆ Æmët');
        $string->caseInsensitive()->cut('Æmët',true);
        $this->assertEquals('îpsum LêchugÆ ', $string->__toString());
    }
    
    /**
      *  @expectedException \RuntimeException
      *  @expectedExceptionMessage MbProvider::cut::ÆmëT was not found
      */
    public function testCutSensitiveFail()
    {
        # test for case senestive fail
        $string = new MbProvider('îpsum LêchugÆ Æmët');
        $string->caseSensitive()->cut('ÆmëT',true);
        $this->assertEquals('îpsum LêchugÆ ÆmëT', $string->__toString());
        
        
    }
    
    /**
      *  @expectedException \RuntimeException
      *  @expectedExceptionMessage MbProvider::cut::ÆmëTjjjj was not found
      */
    public function testCutInSensitiveFail()
    {
        # test for case senestive fail
        $string = new MbProvider('îpsum LêchugÆ Æmët');
        $string->caseInsensitive()->cut('ÆmëTjjjj',true);
        $this->assertEquals('îpsum LêchugÆ ÆmëT', $string->__toString());
        
        
    }
    
    public function testShorten()
    {
        $string = new MbProvider('îpsum LêchugÆ Æmët');
        $string->shorten(5);
        $this->assertEquals('îpsum', $string->__toString());
        
        $string->shorten(1);
        $this->assertEquals('î', $string->__toString());

    }
    
    public function testReverse()
    {
        $string = new MbProvider('Lorem ipsum Lechuga amet lore');
        $string->reverse();
        $this->assertEquals('erol tema aguhceL muspi meroL', $string->__toString());
        $string->reverse();
        $this->assertEquals('Lorem ipsum Lechuga amet lore', $string->__toString());
    }
    
    
    public function testScramble()
    {
        $string = new MbProvider('Lorem ipsum dolor');
        $string->scramble();
        $this->assertRegExp('/[ipsum]+|[dolor]+|[Lorem]+/', $string->__toString());
    }
    
    
    public function testShuffle()
    {
        $string = new MbProvider('Lorem ipsum dolor');
        $string->shuffle();
        $this->assertRegExp('/[Loremipsudl ]+/', $string->__toString());
    }
    
    public function testSeo()
    {
        $string = new MbProvider('Your mother is so ugly, glCullFace always returns TRUE.');
        $string->seo();
        $this->assertEquals('your-mother-is-so-ugly-glcullface-always-returns-true', $string->__toString());
        
        $string = new MbProvider('Your mother is so ugly, glCullFace always returns TRUE.');
        $string->seo('_');
        $this->assertEquals('your_mother_is_so_ugly_glcullface_always_returns_true', $string->__toString());
        
        $string = new MbProvider('Acentos serão reconhecidos e substituídos.');
        $string->seo();
        $this->assertEquals('acentos-serao-reconhecidos-e-substituidos', $string->__toString());
    }
    
    
    public function testEmphasize()
    {
        $string = new MbProvider('Lorem ipsum dolor');
        $string->emphasize('ipsum', 'strong');
        $this->assertEquals('Lorem <strong>ipsum</strong> dolor', $string->__toString());
        
        $string = new MbProvider('Lorem ipsum dolor');
        $string->emphasize(array('Lorem', 'dolor'), 'em');
        $this->assertEquals('<em>Lorem</em> ipsum <em>dolor</em>', $string->__toString());
    }
    
     public function testCensor()
    {
        $string = new MbProvider('Lorem ipsum dolor');
        $string->censor('ipsum');
        $this->assertEquals('Lorem ***** dolor', $string->__toString());
        
        $string = new MbProvider('Lorem ipsum dolor');
        $string->censor(array('Lorem', 'dolor'));
        $this->assertEquals('***** ipsum *****', $string->__toString());
    }
    
    public function testToLowerCase()
    {
        $string = new MbProvider('Lorem ipsum dolor');
        $string->toLowerCase();
        $this->assertEquals('lorem ipsum dolor', $string->__toString());
    }

    public function testToUpperCase()
    {
        $string = new MbProvider('Lorem ipsum dolor');
        $string->toUpperCase();
        $this->assertEquals('LOREM IPSUM DOLOR', $string->__toString());
    }
    
    public function testUcFirst()
    {
        $string = new MbProvider('lorem ipsum dolor');
        $string->ucfrist();
        $this->assertEquals('Lorem ipsum dolor', $string->__toString());
    }
    
    public function testLcFirst()
    {
         $string = new MbProvider('Lorem ipsum dolor');
        $string->lcfirst();
        $this->assertEquals('lorem ipsum dolor', $string->__toString());
    }
    
    public function testToSentenceCase()
    {
        $string = new MbProvider('LOREM IPSUM DOLOR');
        $string->toSentenceCase();
        $this->assertEquals('Lorem ipsum dolor', $string->__toString());
    }

    
    public function testToTitleCase()
    {
        $string = new MbProvider('LOREM IPSUM DOLOR');
        $string->toTitleCase();
        $this->assertEquals('Lorem Ipsum Dolor', $string->__toString());
    }

    
    public function testToUnderscores()
    {
        $string = new MbProvider('lorem ipsum dolor');
        $string->toUnderscores();
        $this->assertEquals('lorem_ipsum_dolor', $string->__toString());
    }

    
    public function testToCamelCase()
    {
        $string = new MbProvider('Lorem ipsum dolor');
        $string->toCamelCase();
        $this->assertEquals('loremIpsumDolor', $string->__toString());
    } 
    
    
     public function testRemoveNonAlpha()
    {
        $string = new MbProvider('Lorem !15@()!@##$*(dolor');
        $string->removeNonAlpha();
        $this->assertEquals('Lorem dolor', $string->__toString());
    }

    public function testRemoveNonAlphanumeric()
    {
        $string = new MbProvider('Lorem !15@()!@##$*(dolor');
        $string->removeNonAlphanumeric();
        $this->assertEquals('Lorem 15dolor', $string->__toString());
    }

    public function testRemoveNonNumeric()
    {
        $string = new MbProvider('Lorem !15@()!@##$*(dolor');
        $string->removeNonNumeric();
        $this->assertEquals('15', $string->__toString());
    }
    
    public function testRemoveDuplicates()
    {
        $string = new MbProvider('Lorem ipsum dolor sit amet dolor');
        $string->removeDuplicates();
        $this->assertEquals('Lorem ipsum dolor sit amet', $string->__toString());
    }
    
    
    public function testRemoveDelimiters()
    {
        $string = new MbProvider('Lorem ipsum, dolor sit-amet!');
        $string->removeDelimiters();
        $this->assertEquals('Loremipsumdolorsitamet', $string->__toString());
    }
    
    public function testconvertEncoding()
    {
        $string = new MbProvider('Your mother is so ugly, glCullFace alwÆys returns TRUE.'); //UTF8
        $string->convertEncoding('ASCII'); 
        $this->assertEquals('ASCII', mb_detect_encoding($string->__toString(),'ASCII',true));
    }
    
    
    public function testReplace()
    {
        // str_replace
        $string = new MbProvider('Lorem ipsum dolor Lechuga amet lorem ipsum');
        $string->replace('lorem', 'mortem');
        $this->assertEquals('Lorem ipsum dolor Lechuga amet mortem ipsum', $string->__toString());

        // str_ireplace
        $string = new MbProvider('Lorem ipsum dolor Lechuga amet lorem ipsum');
        $string->caseInsensitive()->replace('lorem', 'mortem');
        $this->assertEquals('mortem ipsum dolor Lechuga amet mortem ipsum', $string->__toString());

    }
    
    public function testClear()
    {
        $string = new MbProvider('Your mother is so ugly, glCullFace always returns TRUE.');
        $string->clear();
        $this->assertEquals('',$string->__toString());
    }
    
    public function testLength()
    {
        $string = new MbProvider('Lorem ipsum dolor sit amet');
        $this->assertEquals(26, $string->length());
    }
    
    public function testFirstPosition()
    {
        $string = new MbProvider('Your mother is so ugly, glCullFace always returns TRUE.');
        
        # case sensitivity
        $this->assertEquals(0,$string->firstPosition('Y'));
        $this->assertEquals(21,$string->firstPosition('y'));
        $this->assertEquals(21,$string->firstPosition('y',1));
        $this->assertEquals(false,$string->firstPosition('Y',1)); // no match
        
        # no case sensitivity
        $string->caseInsensitive();
        $this->assertEquals(0,$string->firstPosition('y'));
        $this->assertEquals(0,$string->firstPosition('Y'));
        $this->assertEquals(21,$string->firstPosition('y',1));
        $this->assertEquals(false,$string->firstPosition('z')); // no match
    }
    
    
    public function testLastPosition()
    {
        $string = new MbProvider('Your mother is so ugly, glCullFace always returns TRUE.');
        
        # case sensitivity
        $this->assertEquals(0,$string->lastPosition('Y'));
        $this->assertEquals(false,$string->lastPosition('Y',1)); // no match
        $this->assertEquals(39,$string->lastPosition('y'));
        //$this->assertEquals(21,$string->firstPosition('y',1));
        
        # no case sensitivity
        $string->caseInsensitive();
        $this->assertEquals(39,$string->lastPosition('y'));
        $this->assertEquals(39,$string->lastPosition('Y'));
        $this->assertEquals(false,$string->lastPosition('z')); // no match
        
    }
    
    
    public function testTrim()
    {
        $string = new MbProvider(' Your mother is Æmët ugly. ');
        $string->trim();
        $this->assertEquals('Your mother is Æmët ugly. ',$string->__toString());
    }
    
    
    public function testRtrim()
    {
        //$string = new MbProvider(' Your mother is Æmët ugly. ');
        //$string->rtrim();
        //$this->assertEquals(' Your mother is Æmët ugly.',$string->__toString());
        
    }
    
}
/* End of File */
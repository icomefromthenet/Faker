<?php
namespace Faker\Tests\Writer;

use Faker\Project,
    Faker\Io\Io,
    Faker\Components\Writer\Writer,
    Faker\Components\Writer\Io as TemplateIO,
    Faker\Components\Writer\Cache,
    Faker\Components\Writer\Limit,
    Faker\Components\Writer\Sequence,
    Faker\Tests\Base\AbstractProject;

class CacheTest extends AbstractProject
{
    
     //  -------------------------------------------------------------------------
      # Test Cache Class

    /**
      *  @group Writer 
      */
    public function testCacheClass()
    {
        $cache = new Cache();

        $this->assertInstanceOf('\Faker\Components\Writer\Cache',$cache);

        # test adding item

        $this->assertTrue($cache->write('line'));

        $this->assertSame($cache->get(0),'line');

        # remove item
        $cache->remove(0);

        $this->assertSame(count($cache),0);

        # test flush
        $cache->write('line');
        $cache->write('line');
        $cache->write('line');
        $cache->write('line');
        $cache->write('line');

        $this->assertSame(count($cache),5);

        $cache->flush();

        $this->assertSame(count($cache),0);


         # test iterator
        $cache->write('line');
        $cache->write('line');
        $cache->write('line');
        $cache->write('line');
        $cache->write('line');

        $this->assertInstanceOf('\ArrayIterator',$cache->getIterator());

    }

    
}
/* End of File */
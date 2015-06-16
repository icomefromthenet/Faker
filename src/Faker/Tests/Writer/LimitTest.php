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

class LimitTest extends AbstractProject
{
      //  -------------------------------------------------------------------------
      # Test Limit Class
    
    /**
      *  @group Writer 
      */
    public function testLimitNullValue()
    {
        # null  = no limit
        $this->assertInstanceOf('\Faker\Components\Writer\Limit',new Limit(null));
    }

    /**
      * @expectedException \InvalidArgumentException
      * @group Writer
      */
    public function testLimitNegativeVal()
    {
      $file = new Limit(-1);
    }

    /**
      *  @expectedException \InvalidArgumentException
      *  @group Writer
      */
    public function testLimitStringVal()
    {
      $file = new Limit('aaaa');
    }
    
    /**
      *  @group Writer 
      */
    public function testLimitIncrement()
    {
        $file = new Limit(100);

        $this->assertEquals($file->currentAt(),0);

        $file->increment();
        
        $this->assertEquals($file->currentAt(),1);

    }
    
    /**
      *  @group Writer 
      */
    public function testLimitDeincrement()
    {
        $file = new Limit(100);

        $file->increment();
        $file->deincrement();

        $this->assertTrue(true);
    }

    /**
      *  @group Writer 
      */
    public function testLimitIsReached()
    {
        $file = new Limit(5);

        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();


        $this->assertTrue($file->atLimit(), 'Write limt should have been reached');
    }
    
    /**
      *  @group Writer 
      */
    public function testLimitNotReached()
    {
        $file = new Limit(5);

        $file->increment();

        $this->assertFalse($file->atLimit(),'Limit should not have been reached');
    }

    /**
      *  @group Writer 
      */
    public function testLimitReset()
    {
        $file = new Limit(5);

        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();

        $file->reset();

        $this->assertFalse($file->atLimit(),'Limit should not have been reached');
    }

    
    public function testLimitChange()
    {
        $file = new Limit(5);

        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();
  
        $this->assertTrue($file->atLimit(),'Limit should have been reached');
      
        $file->reset();
        $file->changeLimit(6);
        
        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();
        $file->increment();
  
        $this->assertFalse($file->atLimit(),'Limit should not have been reached');
      
    }
    
}
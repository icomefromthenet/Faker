<?php
namespace Faker\Tests\Writer;

use Faker\Project,
    Faker\Io\Io,
    Faker\Components\Writer\Writer,
    Faker\Components\Writer\Io as TemplateIO,
    Faker\Components\Writer\Cache,
    Faker\Components\Writer\Limit,
    Faker\Components\Writer\Sequence,
    Faker\Components\Writer\Stream,
    Faker\Tests\Base\AbstractProject;

class MockSplFile {
    
    public function fwrite(){}
    
    public function fflush(){}
    
}


class StreamTest extends AbstractProject
{
    
    /**
      *  @group Writer 
      */
    public function testProperties()
    {
        
        $header_template = $this->getMockBuilder('Faker\Components\Templating\Template')
                                ->disableOriginalConstructor()
                                ->getMock();
        $footer_template = $this->getMockBuilder('Faker\Components\Templating\Template')
                                ->disableOriginalConstructor()
                                ->getMock();
                                
        $sequence   = new Sequence('schema','table','','sql','{prefix}_{body}_{seq}.{ext}');
        $limit      = new Limit(5);
        $io         = $this->getMockBuilder('Faker\Components\Writer\Io')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $stream = new Stream($header_template,$footer_template,$sequence,$limit,$io);
        
        $this->assertSame($stream->getLimit(),$limit);
        $this->assertSame($stream->getSequence(),$sequence);
        $this->assertSame($stream->getIo(),$io);
        $this->assertSame($stream->getHeaderTemplate(),$header_template);
        $this->assertSame($stream->getFooterTemplate(),$footer_template);
        
    }
    
    
    /**
      *  @group Writer 
      */    
    public function testFirstLineWritesHeader()
    {
        $header_template = $this->getMockBuilder('Faker\Components\Templating\Template')
                                ->disableOriginalConstructor()
                                ->getMock();
        $footer_template = $this->getMockBuilder('Faker\Components\Templating\Template')
                                ->disableOriginalConstructor()
                                ->getMock();
                                
        $sequence   = new Sequence('schema','table','','sql','{prefix}_{body}_{seq}.{ext}');
        $limit      = new Limit(1);
        $io         = $this->getMockBuilder('Faker\Components\Writer\Io')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $stream = new Stream($header_template,$footer_template,$sequence,$limit,$io);
        
        $line = 'my first line';
        
        $file = $this->getMockBuilder('\SplFileInfo')
                     ->disableOriginalConstructor()
                     ->disableAutoload()
                     ->getMock();
                     
        $file_handle = $this->getMockBuilder('Faker\Tests\Writer\MockSplFile')
                            ->getMock();
       
        $file_handle->expects($this->exactly(3))
                    ->method('fwrite');
        
        $file_handle->expects($this->exactly(1))
                    ->method('fflush');
        
        $file->expects($this->once())
             ->method('openFile')
             ->with($this->equalTo('a'))
             ->will($this->returnValue($file_handle));
            
        $io->expects($this->once())
            ->method('write');
        
        $io->expects($this->once())
            ->method('load')
            ->will($this->returnValue($file));
            
        $header_template->expects($this->once())
                        ->method('render')
                        ->will($this->returnValue(''));
       
       $footer_template->expects($this->once())
                        ->method('render')
                        ->will($this->returnValue(''));
       
       
        $stream->write($line);    
    }
    
    
}
/* End of File */
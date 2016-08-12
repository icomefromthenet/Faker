<?php
namespace Faker\Tests\Writer;

use Faker\Project,
    Faker\Io\Io,
    Faker\Components\Writer\Writer,
    Faker\Components\Writer\Io as TemplateIO,
    Faker\Components\Writer\Cache,
    Faker\Components\Writer\Limit,
    Faker\Components\Writer\Sequence,
    Faker\Components\Writer\DatabaseStream,
    Faker\Tests\Base\AbstractProject;
use Doctrine\DBAL\DBALException;

class DatabaseStreamTest extends AbstractProject
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
        
        $encoding = $this->getMockBuilder('Faker\Components\Writer\Encoding')
                    ->disableOriginalConstructor()
                    ->getMock();
                    
                       
        $encoding->expects($this->any())
                 ->method('encode')
                 ->will($this->returnArgument(0));
                                
        $sequence   = new Sequence('schema','table','','sql','{prefix}_{body}_{seq}.{ext}');
        $limit      = new Limit(5);
        $io         = $this->getMockBuilder('Faker\Components\Writer\Io')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $database   = $this->getMockBuilder('Doctrine\\DBAL\\Connection')
                            ->disableOriginalConstructor()
                            ->getMock();
                            
        
        $stream = new DatabaseStream($header_template,$footer_template,$sequence,$limit,$io,$encoding);
        
        $stream->setDatabase($database);
        
        $this->assertSame($stream->getLimit(),$limit);
        $this->assertSame($stream->getSequence(),$sequence);
        $this->assertSame($stream->getIo(),$io);
        $this->assertSame($stream->getHeaderTemplate(),$header_template);
        $this->assertSame($stream->getFooterTemplate(),$footer_template);
        $this->assertSame($stream->getDatabase(),$database);
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
        
        $encoding = $this->getMockBuilder('Faker\Components\Writer\Encoding')
                    ->disableOriginalConstructor()
                    ->getMock();
                    
                       
        $encoding->expects($this->any())
                 ->method('encode')
                 ->will($this->returnArgument(0));
        
                                
        $sequence   = new Sequence('schema','table','','sql','{prefix}_{body}_{seq}.{ext}');
        $limit      = new Limit(1);
        $io         = $this->getMockBuilder('Faker\Components\Writer\Io')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $database   = $this->getMockBuilder('Doctrine\\DBAL\\Connection')
                            ->disableOriginalConstructor()
                            ->setMethods(array('exec'))
                            ->getMock();
        
        $database->expects($this->once())->method('exec')->will($this->returnValue(true));
        
        $stream = new DatabaseStream($header_template,$footer_template,$sequence,$limit,$io,$encoding);
        $stream->setDatabase($database);
        
        $line = 'my first line';
        
            
        $header_template->expects($this->once())
                        ->method('render')
                        ->will($this->returnValue(''));
       
        $footer_template->expects($this->once())
                        ->method('render')
                        ->will($this->returnValue(''));
       
       
        $stream->write($line);
        
        
    }
    
    /**
      *  @group Writer 
      *  @expectedException Faker\Io\Exception
      *  @expectedExceptionMessage Test error message
      */    
    public function testFailesOnDatabaseError()
    {
        $header_template = $this->getMockBuilder('Faker\Components\Templating\Template')
                                ->disableOriginalConstructor()
                                ->getMock();
        $footer_template = $this->getMockBuilder('Faker\Components\Templating\Template')
                                ->disableOriginalConstructor()
                                ->getMock();
        
        $encoding = $this->getMockBuilder('Faker\Components\Writer\Encoding')
                    ->disableOriginalConstructor()
                    ->getMock();
                    
                       
        $encoding->expects($this->any())
                 ->method('encode')
                 ->will($this->returnArgument(0));
        
                                
        $sequence   = new Sequence('schema','table','','sql','{prefix}_{body}_{seq}.{ext}');
        $limit      = new Limit(1);
        $io         = $this->getMockBuilder('Faker\Components\Writer\Io')
                            ->disableOriginalConstructor()
                            ->getMock();
        
        $database   = $this->getMockBuilder('Doctrine\\DBAL\\Connection')
                            ->disableOriginalConstructor()
                            ->setMethods(array('exec'))
                            ->getMock();
        
        $database->expects($this->once())->method('exec')->will($this->throwException(new DBALException('Test error message')));
        
        $stream = new DatabaseStream($header_template,$footer_template,$sequence,$limit,$io,$encoding);
        $stream->setDatabase($database);
        
        $line = 'my first line';
        
            
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
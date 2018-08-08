<?php
namespace Faker\Tests\Engine\Common\Selector;

use Faker\Components\Engine\Common\TypeRepository;
use Faker\Components\Engine\Common\Builder\NodeBuilder;
use Faker\Components\Engine\Common\Builder\SelectorAlternateBuilder;
use Faker\Components\Engine\Common\Builder\SelectorRandomBuilder;
use Faker\Components\Engine\Common\Builder\SelectorSwapBuilder;
use Faker\Components\Engine\Common\Builder\SelectorWeightBuilder;
use Faker\Tests\Base\AbstractProject;

class SelectorDefinitionTest extends AbstractProject
{
    
    public function testImplementsInterfacesCorrectly()
    {
        
        $name = 'fieldA';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');    
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock(); 
        $template  = $this->getMockBuilder('Faker\Components\Templating\Loader')->disableOriginalConstructor()->getMock();       
        $repo      = $this->getMockBuilder('Faker\Components\Engine\Common\TypeRepository')->getMock(); 
        
        $alternateBuilder = new SelectorAlternateBuilder($name,$event,$repo,$utilities,$generator,$locale,$database,$template);
        $randomBuilder    = new SelectorRandomBuilder($name,$event,$repo,$utilities,$generator,$locale,$database,$template);
        $swapBuilder      = new SelectorSwapBuilder($name,$event,$repo,$utilities,$generator,$locale,$database,$template);
        $weightBuilder    = new SelectorWeightBuilder($name,$event,$repo,$utilities,$generator,$locale,$database,$template);
        
        $this->assertInstanceOf('Faker\Components\Engine\Common\Builder\TypeDefinitionInterface',$alternateBuilder);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Builder\TypeDefinitionInterface',$randomBuilder);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Builder\TypeDefinitionInterface',$swapBuilder);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Builder\TypeDefinitionInterface',$weightBuilder);
    }
    
    
    
    public function testDescribeImplemented()
    {
        $name = 'fieldA';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');    
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock(); 
        $template  = $this->getMockBuilder('Faker\Components\Templating\Loader')->disableOriginalConstructor()->getMock();       
        $repo      = $this->getMockBuilder('Faker\Components\Engine\Common\TypeRepository')->getMock(); 
        
        $alternateBuilder = new SelectorAlternateBuilder($name,$event,$repo,$utilities,$generator,$locale,$database,$template);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Builder\NodeBuilder',$alternateBuilder->describe());
        $this->assertEquals($alternateBuilder,$alternateBuilder->describe()->getParent());
        
        $randomBuilder    = new SelectorRandomBuilder($name,$event,$repo,$utilities,$generator,$locale,$database,$template);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Builder\NodeBuilder',$randomBuilder->describe());
        $this->assertEquals($randomBuilder,$randomBuilder->describe()->getParent());
        
        $weightBuilder    = new SelectorWeightBuilder($name,$event,$repo,$utilities,$generator,$locale,$database,$template);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Builder\NodeBuilder',$weightBuilder->describe());
        $this->assertEquals($weightBuilder,$weightBuilder->describe()->getParent());
        
        # use swapAt not describe 
        $swapBuilder      = new SelectorSwapBuilder($name,$event,$repo,$utilities,$generator,$locale,$database,$template);
        $this->assertInstanceOf('Faker\Components\Engine\Common\Builder\TypeBuilder',$swapBuilder->swapAt(100));
        $this->assertEquals($swapBuilder,$swapBuilder->swapAt(100)->getParent());
    }
    
    
    public function testAlternateGetNode()
    {
        $name = 'fieldA';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');    
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock(); 
        $template  = $this->getMockBuilder('Faker\Components\Templating\Loader')->disableOriginalConstructor()->getMock();       
        $repo      = $this->getMockBuilder('Faker\Components\Engine\Common\TypeRepository')->getMock(); 
        
        $alternateBuilder = new SelectorAlternateBuilder($name,$event,$repo,$utilities,$generator,$locale,$database,$template);
        
        $this->assertInstanceOF('Faker\Components\Engine\Common\Composite\SelectorNode',$alternateBuilder->getNode());
        $this->assertInstanceOF('Faker\Components\Engine\Common\Selector\AlternateSelector',$alternateBuilder->getNode()->getInternal());
    }
    
    
    public function testWeightGetNode()
    {
        $name = 'fieldA';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');    
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock(); 
        $template  = $this->getMockBuilder('Faker\Components\Templating\Loader')->disableOriginalConstructor()->getMock();       
        $repo      = $this->getMockBuilder('Faker\Components\Engine\Common\TypeRepository')->getMock(); 
        
        $weightBuilder = new SelectorWeightBuilder($name,$event,$repo,$utilities,$generator,$locale,$database,$template);
        $weightBuilder->weight(1.0);
        
        $this->assertInstanceOF('Faker\Components\Engine\Common\Composite\SelectorNode',$weightBuilder->getNode());
        $this->assertInstanceOF('Faker\Components\Engine\Common\Selector\PickSelector',$weightBuilder->getNode()->getInternal());
        $this->assertEquals(1.0,$weightBuilder->getNode()->getInternal()->getOption('probability'));
    }
    
    public function testRandomGetNode()
    {
        $name = 'fieldA';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');    
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock(); 
        $template  = $this->getMockBuilder('Faker\Components\Templating\Loader')->disableOriginalConstructor()->getMock();       
        $repo      = $this->getMockBuilder('Faker\Components\Engine\Common\TypeRepository')->getMock(); 
        
        $randomBuilder = new SelectorRandomBuilder($name,$event,$repo,$utilities,$generator,$locale,$database,$template);
        
        $this->assertInstanceOF('Faker\Components\Engine\Common\Composite\SelectorNode',$randomBuilder->getNode());
        $this->assertInstanceOF('Faker\Components\Engine\Common\Selector\RandomSelector',$randomBuilder->getNode()->getInternal()); 
        
    }
    
    
    public function testSwapGetNode()
    {
        $name = 'fieldA';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        $generator = $this->createMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->createMock('\Faker\Locale\LocaleInterface');    
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock(); 
        $template  = $this->getMockBuilder('Faker\Components\Templating\Loader')->disableOriginalConstructor()->getMock();       
        $repo      = $this->getMockBuilder('Faker\Components\Engine\Common\TypeRepository')->getMock(); 
        
        $swapBuilder = new SelectorSwapBuilder($name,$event,$repo,$utilities,$generator,$locale,$database,$template);
        
        $typeBuilder = $swapBuilder->swapAt(100);
    
        $this->assertInstanceOf('Faker\Components\Engine\Common\Builder\TypeBuilder',$typeBuilder);
        
    }
    
}
/*End of file */

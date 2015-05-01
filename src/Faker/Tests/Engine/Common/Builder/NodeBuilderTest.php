<?php
namespace Faker\Tests\Engine\Common\Builder;

use Faker\Components\Engine\Common\TypeRepository;
use Faker\Components\Engine\Common\Builder\NodeBuilder;
use Faker\Tests\Base\AbstractProject;

class NodeBuilderTest extends AbstractProject
{
    
    public function testImplementsInterfacesCorrectly()
    {
        
        $name = 'fieldA';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');    
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock(); 
        $template  = $this->getMockBuilder('Faker\Components\Templating\Loader')->disableOriginalConstructor()->getMock();       
        $repo      = $this->getMockBuilder('Faker\Components\Engine\Common\TypeRepository')->getMock(); 
        
        $builder = new NodeBuilder($name,$event,$repo,$utilities,$generator,$locale,$database,$template);
    }
    
    
    
    public function testFieldListInterfaceAndTypeRepointegration()
    {
         
        $name = 'fieldA';
        
        $utilities = $this->getMockBuilder('Faker\Components\Engine\Common\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        $generator = $this->getMock('\PHPStats\Generator\GeneratorInterface');
        $locale    = $this->getMock('\Faker\Locale\LocaleInterface');    
        $event     = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        $database  = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock(); 
        $template  = $this->getMockBuilder('Faker\Components\Templating\Loader')->disableOriginalConstructor()->getMock();       
        $repo      =  new TypeRepository();
        
        
        $builder = new NodeBuilder($name,$event,$repo,$utilities,$generator,$locale,$database,$template);
        
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\AlphaNumericTypeDefinition',$builder->fieldAlphaNumeric());
        $this->assertEquals($builder,$builder->fieldAlphaNumeric()->getParent());
        
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\ClosureTypeDefinition',$builder->fieldClosure());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\NullTypeDefinition',$builder->fieldNull());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\AutoIncrementTypeDefinition',$builder->fieldAutoIncrement());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\RangeTypeDefinition',$builder->fieldRange());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\BooleanTypeDefinition',$builder->fieldBoolean());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\ConstantNumberTypeDefinition',$builder->fieldConstant());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\NumericTypeDefinition',$builder->fieldNumeric());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\TextTypeDefinition',$builder->fieldText());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\DateTypeDefinition',$builder->fieldDate());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\EmailTypeDefinition',$builder->fieldEmail());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\UniqueNumberTypeDefinition',$builder->fieldUniqueNumber());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\UniqueStringTypeDefinition',$builder->fieldUniqueString());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\NamesTypeDefinition',$builder->fieldPeople());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\CitiesTypeDefinition',$builder->fieldCity());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\CountryTypeDefinition',$builder->fieldCountry());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\TemplateTypeDefinition',$builder->fieldTemplate());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\RegexTypeDefinition',$builder->fieldRegex());
        $this->assertInstanceOf('\\Faker\\Components\\Engine\\Common\\Builder\\FromSourceTypeDefinition',$builder->fieldFromSource());
        
    }
    
}
/*End of file */

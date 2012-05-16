<?php
namespace Faker\Tests\Faker\Type;

use Faker\Components\Faker\Type\Email,
    Faker\Components\Faker\Utilities,
    Faker\Tests\Base\AbstractProject;    

class EmailTest extends AbstractProject
{
    
    public function testTypeExists()
    {
        
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();

        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
      
            
        $type = new Email($id,$parent,$event,$utilities);
        
        $this->assertInstanceOf('\\Faker\\Components\\Faker\\TypeInterface',$type);
    
    }
    
    //--------------------------------------------------------------------------
    
    public function testConfig()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock(); 
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Email($id,$parent,$event,$utilities);
        $config = array('format' =>'xxxx','domains' => 'au,com.au');
        
        
        $options = $type->merge($config);        
        
        $this->assertEquals($options['format'],$config['format']);
        $this->assertSame($options['domains'],array('au','com.au'));
    }
    
    //  -------------------------------------------------------------------------
    
    /**
      *  @expectedException \Faker\Components\Faker\Exception
      *  @expectedExceptionMessage The child node "format" at path "config" must be configured
      */
    public function testConfigMissingFormat()
    {
        $id = 'table_two';
        
        $utilities = $this->getMockBuilder('Faker\Components\Faker\Utilities')
                          ->disableOriginalConstructor()
                          ->getMock();
        
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Email($id,$parent,$event,$utilities);
        $config = array(); 
        
        $options = $type->merge($config);        
        
        
    }
    
    //  -------------------------------------------------------------------------
    
    
    public function testGenerate()
    {
        $id = 'table_two';
        $project = $this->getProject();
       
        $utilities = new Utilities($project);
        
        
        $parent = $this->getMockBuilder('Faker\Components\Faker\Composite\CompositeInterface')
                        ->getMock();
                        
        $event = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                      ->getMock();
            
        $type = new Email($id,$parent,$event,$utilities);
        $type->setOption('format','{fname}\'{lname}{alpha1}@{alpha2}.{domain}');
        $type->setOption('alpha1','ccCCC');
        $type->setOption('alpha2','xxxx');
      
               
        $type->validate(); 
         
        $value = $type->generate(1,array());
    }
    
    
}
/*End of file */

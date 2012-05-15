<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Exception as FakerException;
use Faker\Components\Faker\Utilities;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use \PDO;

class Email extends Type
{
    protected $valid_suffixes;
    
    


    //---------------------------------------------------------------
    /**
     * Generate an Email address
     * 
     * @return string 
     */
    public function generate($rows, $values = array())
    {
        $format = $this->getOption('format');
        
        # fetch names values from database
        
        $conn = $this->utilities->getGeneratorDatabase();
        $sql = "SELECT * FROM person_names ORDER BY RANDOM() LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();    
   
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        # fetch a random name from the db
        $fname =$result['fname'];
        $lname =$result['lname'];
        $inital = $result['middle_initial'];
        
        
        # parse name data into format
        $format = preg_replace('/{fname}/', $fname,$format);
        $format = preg_replace('/{lname}/', $lname,$format);
        $format = preg_replace('/{inital}/',$inital,$format);
       
        # parse the domain data into format 
        $domains = $this->getOption('domains');
        $rand_key = array_rand($domains,1); 
        $format = preg_replace('/{domain}/',$domains[$rand_key],$format);
        
        # parse names param
        $params = $this->getNameParams();
        
        foreach($params as $param => $value) {
            $format = preg_replace('/{'.$param.'}/',$this->utilities->generateRandomAlphanumeric($value),$format);
        }
        
        return $format;
    }
    
    //  -------------------------------------------------------------------------

    public function toXml()
    {
       return '<datatype name="'.$this->getId().'"></datatype>' . PHP_EOL;
    }
    
    //  -------------------------------------------------------------------------

    
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');

        $rootNode
            ->children()
                ->scalarNode('domains')
                    ->defaultValue(array('edu','com','org','ca','net','co.uk','com.au','biz','info'))
                    ->setInfo('a list of domains to use')
                    ->setExample('edu,com,org,ca,net,co.uk,com.au,biz,info')
                    ->validate()
                        ->ifString()
                        ->then(function($v){
                            # parse the values into an array
                            $tokens = new \Faker\Components\Faker\TokenIterator($v,',');
                            $domains = array();
                            foreach($tokens as $domain) {
                                $domains[] = $domain;
                            }
                            unset($tokens);

                            return $domains;
                        })
                    ->end()
                ->end()
                ->scalarNode('format')
                    ->isRequired()
                    ->setInfo('Format to use to generate addresses')
                    ->setExample('{fname}{lname}{alpha}@{alpha}.{domain}')
                ->end()
                
            ->end();
            
        return $treeBuilder;
    }
    
    //  -------------------------------------------------------------------------

    public function merge($config)
    {
        try {
            
            $processor = new Processor();
            return $processor->processConfiguration($this, array('config' => $config));
            
        }catch(InvalidConfigurationException $e) {
            
            throw new FakerException($e->getMessage());
        }
    }
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        $this->options = $this->merge($this->options);
        return true;
    }
    
    //  -------------------------------------------------------------------------

    
    
    public function setOption($name,$option)
    {
	if(in_array($name,array('format','domains')) === false) {
            $this->addNameParam($name,$option);
            
        }else {
            $this->options[$name] = $option;    
        }
        
    }
    
    //  -------------------------------------------------------------------------
    
    protected $name_params = array();
        
    public function getNameParams()
    {
        return $this->name_params;
    }
    
    public function addNameParam($param,$value)
    {
        $this->name_params[$param] = $value;
    }
    
    //  -------------------------------------------------------------------------
    
}
/* End of file */
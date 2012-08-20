<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder,
    \PDO;

class Email extends Type
{
    protected $valid_suffixes;
    
    /**
      *  @var integer the number of names in db 
      */
    protected $name_count;
    
    //---------------------------------------------------------------
    /**
     * Generate an Email address
     * 
     * @return string 
     */
    public function generate($rows, $values = array())
    {
        $format = $this->getOption('format');
        $conn = $this->utilities->getGeneratorDatabase();
        
        # fetch names values from database
        if($this->name_count === null) {
            
            $sql              = "SELECT count(id) as nameCount FROM person_names ORDER BY id";
            $stmt             = $conn->prepare($sql);
            $stmt->execute();    
            $result           = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->name_count = (integer) $result['nameCount'];
            
        }
        
        if($this->name_count <= 0) {
            throw new FakerException('Names:: no names found in db');
        }
        
        
        $offset = ceil($this->generator->generate(0,($this->name_count -1)));
        
        $sql  = "SELECT * FROM person_names ORDER BY id LIMIT 1 OFFSET ".$offset;
        $stmt = $conn->prepare($sql);
        $stmt->execute();    
   
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        # fetch a random name from the db
        $fname  = $result['fname'];
        $lname  = $result['lname'];
        $inital = $result['middle_initial'];

        
        
        # parse name data into format
        $format = preg_replace('/{fname}/', $fname,$format);
        $format = preg_replace('/{lname}/', $lname,$format);
        $format = preg_replace('/{inital}/',$inital,$format);
       
        # parse the domain data into format 
        $domains = $this->getOption('domains');
        
        #adjust for 0 based array
        if(($domin_count = count($domains)) > 1) {
            $domin_count = $domin_count -1;
        }
        
        $rand_key = $this->generator->generate(0,$domin_count);
        $format = preg_replace('/{domain}/',$domains[$rand_key],$format);
        
        # parse names param
        $params = $this->getOption('params');
        
        
        
        
        foreach($params as $param => $value) {
            $format = preg_replace('/{'.preg_quote($param).'}/',
                                   $this->utilities->generateRandomAlphanumeric($value,$this->getGenerator(),$this->getLocale()),
                                   $format
                                );
        }
        
        return $format;
    }
    
    //  -------------------------------------------------------------------------

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition 
     */
    public function getConfigExtension(ArrayNodeDefinition $rootNode)
    {
        return $rootNode
            ->children()
                ->scalarNode('params')
                    ->info('a json name params to use')
                    ->defaultValue(array())
                    ->treatNullLike(array())
                    ->validate()
                        ->ifString()
                        ->then(function($v){
                            return json_decode($v,true);
                        })
                    ->end()
                ->end()
                ->scalarNode('domains')
                    ->defaultValue(array('edu','com','org','ca','net','co.uk','com.au','biz','info'))
                    ->info('a list of domains to use')
                    ->example('edu,com,org,ca,net,co.uk,com.au,biz,info')
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
                    ->info('Format to use to generate addresses')
                    ->example('{fname}{lname}{alpha}@{alpha}.{domain}')
                ->end()
            ->end();
    }
    
    //  -------------------------------------------------------------------------

    public function validate()
    {
        return true;
    }
    
    //  -------------------------------------------------------------------------
    
}
/* End of file */
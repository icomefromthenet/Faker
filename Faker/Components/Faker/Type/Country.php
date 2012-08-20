<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder,
    \PDO;

class Country extends Type
{
   /**
     *  @var the number of countries in db 
     */
    protected $country_count;
   
   
   
    //---------------------------------------------------------------
    /**
     * Generate an a city string
     * 
     * @return string 
     */
    public function generate($rows, $values = array())
    {
        $countries = $this->getOption('countries');
       
        # fetch names values from database
        if($countries == null) {
           $result = $this->randomCountry();
        }
        else {
            
           $result = $this->fromCode($countries);
        }
   
       
        return $result;
    }
    
    
    public function randomCountry()
    {
         # get cpunt 
        $conn = $this->utilities->getGeneratorDatabase();

        if($this->country_count === null) {
             $sql                 = "SELECT count(name) as countryCount FROM countries ORDER BY name";
             $stmt                = $conn->executeQuery($sql);
             $result              = $stmt->fetch(PDO::FETCH_ASSOC);
                
             $this->country_count = (integer) $result['countryCount']; 
        }
       
       
        if($this->country_count <= 0) {
            throw new FakerException('Country::no countries found in db');
        }
        
        # fetch a country
        $offset = ceil($this->generator->generate(0,($this->country_count -1)));
        $sql    = "SELECT * FROM countries ORDER BY name LIMIT 1 OFFSET ". $offset;
        $stmt   = $conn->executeQuery($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        # fetch a random name from the db
        return $result['name'];
        
    }
    
    
    public function fromCode(array $code)
    {
        $conn  = $this->utilities->getGeneratorDatabase();
        
        if($this->country_count === null) {
        
            $sql                 = "SELECT count(name) as countryCount FROM countries WHERE ".$conn->quoteIdentifier('code')." IN (?) ORDER BY name";
            $stmt                = $conn->executeQuery($sql,
                                                        array($code),
                                                        array(\Doctrine\DBAL\Connection::PARAM_STR_ARRAY));
            $result              = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->country_count = (integer) $result['countryCount'];
        
        }
        
        if($this->country_count <= 0 || $this->country_count !== count($code)) {
            throw new FakerException('Country::no countries found in db for '. implode(',',$code)); 
        }
        
        if($this->country_count === 1) {
            $offset = 0;
        }else {
            $offset = ceil($this->generator->generate(0,($this->country_count -1)));
        }
        
        $sql  = "SELECT * FROM countries WHERE ".$conn->quoteIdentifier('code')." IN (?) ORDER BY name LIMIT 1 OFFSET ".$offset;
        $stmt = $conn->executeQuery($sql,
                                    array($code),
                                    array(\Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
        );
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        # fetch a random name from the db
        return $result['name'];
        
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
                ->scalarNode('countries')
                    //ftp://ftp.fu-berlin.de/doc/iso/iso3166-countrycodes.txt
                    ->defaultValue(null)
                    ->info('a list of country codes to use')
                    ->example('AU,US,UK')
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
            ->end();
    }
    
    //  -------------------------------------------------------------------------
    
    public function validate()
    {
        return true;
    }
    
}
/* End of file */
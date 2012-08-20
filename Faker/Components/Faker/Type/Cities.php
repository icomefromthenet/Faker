<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder,
    \PDO;

class Cities extends Type
{
    //---------------------------------------------------------------
    /**
     * Generate an a city string
     * 
     * @return string 
     */
    public function generate($rows, $values = array())
    {
        $countries = $this->getOption('countries');
        $x = 0;
        
        # fetch names values from database
        $conn = $this->utilities->getGeneratorDatabase();
        $sql = "SELECT count(geonameid) as rcount FROM world_cities WHERE ".$conn->quoteIdentifier('country_code')." IN (?)  ORDER BY geonameid";
        
        $stmt = $conn->executeQuery($sql,
                                array($countries),
                                array(\Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
        );
   
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        # fetch a random name from the db
        $collection_size =  (integer) $result['rcount'];
        
        if($collection_size > 0) {
            
            # want to avoid negative set size.
            if($collection_size === 1) {
                $x = 0;
            } else {
                $x = ceil($this->generator->generate(0,($collection_size-1)));    
            }
            
            $sql = "SELECT * FROM world_cities WHERE ".$conn->quoteIdentifier('country_code')." IN (?) ORDER BY geonameid LIMIT 1 OFFSET $x";
            
            $stmt = $conn->executeQuery($sql,
                                array($countries),
                                array(\Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
            );
   
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['name'];
            
        }else {
            throw new FakerException('Cities::no cities found for countries '.implode(',',$countries));
        }
        
        
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
                    ->defaultValue(array('AU,US,UK'))
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
    
    //  -------------------------------------------------------------------------
}
/* End of file */
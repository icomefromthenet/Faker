<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder,
    \PDO;

class Country extends Type
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
        $conn = $this->utilities->getGeneratorDatabase();
        
        # fetch names values from database
        if($countries == null) {
            $sql = "SELECT * FROM countries ORDER BY RANDOM() LIMIT 1";
            $stmt = $conn->executeQuery($sql);
            
        }
        else {
            $sql = "SELECT * FROM countries WHERE ".$conn->quoteIdentifier('code')." IN (?)  ORDER BY RANDOM() LIMIT 1";
            $stmt = $conn->executeQuery($sql,
                                    array($countries),
                                    array(\Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
            );
            
        }
   
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        # fetch a random name from the db
        return $result['name'];
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
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition 
     */
    public function getConfigExtension(ArrayNodeDefinition $rootNode)
    {
        return $rootNode
            ->children()
                ->scalarNode('countries')
                    //ftp://ftp.fu-berlin.de/doc/iso/iso3166-countrycodes.txt
                    ->defaultValue(null)
                    ->setInfo('a list of country codes to use')
                    ->setExample('AU,US,UK')
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
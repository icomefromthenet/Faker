<?php
namespace Faker\Components\Engine\Common\Type;

use PDO;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Original\Utilities;
use Faker\Components\Engine\Common\TokenIterator;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Doctrine\DBAL\Connection;

/**
 * City names from Geonames database
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class Cities extends Type
{
    
    /**
      *  @var Doctrine\DBAL\Connection 
      */
    protected $database;
    
    /**
      *  Class Constructor
      *
      *  @param Doctrine\DBAL\Connection $conn
      */
    public function __construct(Connection $conn)
    {
        $this->database = $conn;
    }
    
    
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
        $conn = $this->database;
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
                $x = ceil($this->getGenerator()->generate(0,($collection_size-1)));    
            }
            
            $sql = "SELECT * FROM world_cities WHERE ".$conn->quoteIdentifier('country_code')." IN (?) ORDER BY geonameid LIMIT 1 OFFSET $x";
            
            $stmt = $conn->executeQuery($sql,
                                array($countries),
                                array(\Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
            );
   
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['name'];
            
        }else {
            throw new EngineException('Cities::no cities found for countries '.implode(',',$countries));
        }
        
        
    }
    
    //  -------------------------------------------------------------------------

    /**
     * Generates the configuration tree builder.
     *
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('config');  
    
        $rootNode
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
                            $tokens = new TokenIterator($v,',');
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
        
        return $treeBuilder;    
    }
  
}
/* End of file */
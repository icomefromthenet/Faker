<?php
namespace Faker\Components\Engine\Common\Type;

use PDO;
use Faker\Components\Engine\EngineException;
use Faker\Components\Engine\Original\Utilities;
use Faker\Components\Engine\Common\TokenIterator;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Doctrine\DBAL\Connection;

/**
 * Name database type
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.4
 *
 */
class Names extends Type
{

    /**
      *  @var integer the number of names in db 
      */
    protected $name_count;
   
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
    
   
    //  -------------------------------------------------------------------------

    /**
     * Generate a value
     * 
     * @return string 
     */
    public function generate($rows,$values = array())
    {
        $conn = $this->database;
        
        
        if($this->name_count === null) {
            
            $sql              = "SELECT count(id) as nameCount FROM person_names ORDER BY id";
            $stmt             = $conn->prepare($sql);
            $stmt->execute();    
            $result           = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->name_count = (integer) $result['nameCount'];
            
        }
        
        if($this->name_count <= 0) {
            throw new EngineException('Names:: no names found in db');
        }
        
        
        $offset = ceil($this->getGenerator()->generate(0,($this->name_count -1)));
        
        $sql = "SELECT * FROM person_names ORDER BY id LIMIT 1 OFFSET ".$offset;
        $stmt = $conn->prepare($sql);
        $stmt->execute();    
   
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        # fetch a random name from the db
        $fname =$result['fname'];
        $lname =$result['lname'];
        $inital = $result['middle_initial'];
        
        
        # parse data into format
        
        $format = $this->getOption('format');
    
        $format = preg_replace('/{fname}/', $fname,$format);
        $format = preg_replace('/{lname}/', $lname,$format);
        $format = preg_replace('/{inital}/',$inital,$format);
       
         
        return $format;
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
                ->scalarNode('format')
                    ->isRequired()
                    ->info('Names Format to use')
                    ->example('{fname} {inital} {lname}')
                ->end()
            ->end();
            
        return $treeBuilder;
    }
    
}
/* End of file */
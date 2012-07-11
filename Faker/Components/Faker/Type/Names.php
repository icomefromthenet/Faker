<?php
namespace Faker\Components\Faker\Type;

use Faker\Components\Faker\Exception as FakerException,
    Faker\Components\Faker\Utilities,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\Config\Definition\Builder\TreeBuilder,
    \PDO;

class Names extends Type
{

    /**
      *  @var integer the number of names in db 
      */
    protected $name_count;
   
    //  -------------------------------------------------------------------------

    /**
     * Generate a value
     * 
     * @return string 
     */
    public function generate($rows,$values = array())
    {
        $conn = $this->utilities->getGeneratorDatabase();
        
        
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
                ->scalarNode('format')
                    ->isRequired()
                    ->info('Names Format to use')
                    ->example('{fname} {inital} {lname}')
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
<?php
namespace Faker\Components\Faker;

use Faker\Parser\Parser\XML as BaseXMLParser;
use Faker\Parser\FileInterface;
use Faker\Parser\ParseOptions;
use Faker\Components\Faker\Exception as FakerException;
use Faker\Parser\Exception as ParserException;

class SchemaParser extends BaseXMLParser
{
    
    /**
      *  @var Faker\Components\Faker\Builder 
      */
    protected $builder;
    
    //  ----------------------------------------------------------------------------
    # Class Constructor
    
    
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }
    
    //  ----------------------------------------------------------------------------
    # Xml Parser Handlers
    
    /**
      * The element handler functions for the XML parser
      * 
      * @link  http://www.php.net/manual/en/function.xml-set-element-handler.php
      */
    protected function xmlStartTag($parser, $name, $attribs)
    {
        
        # convert attributes to phpType
        
        foreach($attribs as &$value) {
            $value = self::phpize($value);
        }
        
        switch($name) {
            case 'writer' :
               
                if(isset($attribs['format']) === false) {
                    throw new FakerException('Writter Tag Missing Format');
                }
               
                if(isset($attribs['platform']) === false) {
                    throw new FakerException('Writer Tag Missing Platform');
                }
                
                $platform = $attribs['platform'];
                $format   = $attribs['format'];
                
                unset($attribs['format']);
                unset($attribs['platform']);
                
                $this->builder->addWriter($platform,$format,$attribs);
                
                
                
            break;
            case 'schema':    
                
                if(isset($attribs['name']) === false) {
                    throw new FakerException('Schema Tag Missing Name');
                }
                
                $this->builder->addSchema($attribs['name'],$attribs);
            break;
            case 'table':    
            
                if(isset($attribs['name']) === false) {
                    throw new FakerException('Table Tag Missing Name');
                }
                
                $this->builder->addTable($attribs['name'],$attribs);
          
              
            break;
            case 'column':    
           
                if(isset($attribs['name']) === false) {
                    throw new FakerException('Column Tag Missing Name');
                }
                
                $this->builder->addColumn($attribs['name'],$attribs);
           
            break;
            case 'datatype':    
                
                if(isset($attribs['name']) === false) {
                    throw new FakerException('Datatype Tag Missing Name');
                }
                
                $this->builder->addType($attribs['name'],$attribs);
            
            break;
            case 'option':    
                
                if(isset($attribs['name']) === false) {
                    throw new FakerException('Have Type Option Tag Missing Name Attribute');
                }
                
                if(isset($attribs['value']) === false) {
                    throw new FakerException('Have Type Option Tag Missing Value Attribute');
                }
                
                
                $this->builder->setTypeOption($attribs['name'],$attribs['value']);

            break;
            case 'foreign-key' :
                
                if(isset($attribs['name']) === false) {
                    throw new FakerException('Foreign-key must have a name unique name try foreignTable.foriegnColumn');
                }
                
                $this->builder->addForeignKey($attribs['name'],$attribs);
                
            break;    
            case 'alternate';
            case 'pick';
            case 'random';
            case 'when':
            case 'swap':    
                $this->builder->addSelector($name,$attribs);
            break;
            default: throw new FakerException(sprintf('Tag name %s unknown',$name));
        }
       
             
    }

    //  ----------------------------------------------------------------------------
    
    /**
      * The element handler functions for the XML parser
      *
      *  @link http://www.php.net/manual/en/function.xml-set-element-handler.php
      */
    protected function xmlEndTag($parser, $name)
    {
        switch($name) {
            case 'column':    
            case 'table':    
            case 'schema':    
            case 'datatype': 
            case 'alternate';
            case 'pick';
            case 'random';
            case 'when':
            case 'swap':
            case 'foreign-key':
                $this->builder->end();
            break;
        }
        
    }

    //  ----------------------------------------------------------------------------
    
    /**
      * The character data handler function (Tag Content)
      * 
      *  @link http://www.php.net/manual/en/function.xml-set-character-data-handler.php
      */
    protected function xmlTagContent($parser, $char_data)
    {
      
      
    }
    
    
    //  ----------------------------------------------------------------------------
    # Start the parsing operation
    
    /**
      *  Starts parsing a file
      *
      *  @param FileInterface $file
      *  @return array() the xml data
      *  @access public
      */
    public function parse(FileInterface $file, ParseOptions $options)
    {
        try {
           parent::parse($file,$options);
        } catch(ParserException $e) {
            throw new FakerException($e->getMessage());
        }
        
        return $this->builder;
    }    
    
}
/* End of File */
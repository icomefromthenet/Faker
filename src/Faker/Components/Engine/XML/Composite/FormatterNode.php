<?php
namespace Faker\Components\Engine\XML\Composite;

use PHPStats\Generator\GeneratorInterface;
use Faker\Components\Engine\Common\GeneratorCache;
use Faker\Components\Engine\Common\Composite\FormatterNode as BaseNode;
use Faker\Components\Engine\Common\OptionInterface;
use Faker\Components\Engine\Common\Composite\SerializationInterface;
use Faker\Components\Engine\Common\Type\TypeInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Locale\LocaleInterface;

/**
  *  FormatterNode
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class FormatterNode extends BaseNode implements OptionInterface, SerializationInterface, TypeInterface
{
    
    /**
     *  @var Faker\Locale\LocaleInterface
    */
    protected $locale;
    
    /**
     *  @var PHPStats\Generator\GeneratorInterface
    */
    protected $generator;
    
    /**
     *  @var Faker\Components\Engine\Common\Utilities
    */
    protected $utilities;
    
    
    public function setOption($name,$value)
    {
        $this->getInternal()->setOption($name,$value);
    }
    
    public function getOption($name)
    {
        return $this->getInternal()->getOption($name);
    }
    
    public function hasOption($name)
    {
        return $this->getInternal()->hasOption($name);
    }
    
    
    public function getConfigTreeBuilder()
    {
        return null;        
    }
    
    //-------------------------------------------------------
    # TypeInterface
    
    
    public function getUtilities()
    {
        return $this->utilities;
    }
    
    public function setUtilities(Utilities $util)
    {
        $this->utilities = $util;
        
        return $this;
    }
    
    public function getGenerator()
    {
        return $this->generator;
    }
    
    public function setGenerator(GeneratorInterface $generator)
    {
        $this->generator = $generator;
        
        return $this;
    }

    public function setLocale(LocaleInterface $locale)
    {
        $this->locale = $locale;
        
        return $this;
    }
    
    public function getLocale()
    {
        return $this->locale;
    }
    
    
    
    //-------------------------------------------------------
    # SerializationInterface
    
   /**
    * Convert the formatter to xml representation
    *
    * @return string the xml rep
    * @access public
    */
    public function toXml()
    {
        return '<writer platform="'.$this->getInternal()->getPlatform()->getName().'" format="'.$this->getInternal()->getName().'" />';
    }
    
    public function toPHP(array $aCode)
    {
        return null;    
    }
    
    //-------------------------------------------------------
    # SerializationInterface
    
    /**
      *  Generate a value
      *
      *  @param integer $rows the current row number
      *  @param mixed $array list of values generated in context
      */
    public function generate($rows,&$values = array(),$last = array())
    {
        return null;
    }
    
    
    /**
      *  Sets the Generator Result Cache
      *
      *  @access public
      *  @param GeneratorCache $cache
      */
    public function setResultCache(GeneratorCache $cache)
    {
        
    }
    
    /**
      *  Return the Generator Result Cache
      *
      *  @access public
      *  @return GeneratorCache
      */
    public function getResultCache()
    {
        
    }
    
    
}
/* End of File */
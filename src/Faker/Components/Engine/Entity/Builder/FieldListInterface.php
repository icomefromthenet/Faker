<?php
namespace Faker\Components\Engine\Entity\Builder;

/**
  *  List of type fields 
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
interface FieldListInterface 
{
    
    /**
      *  Return an Alphnumeric field for configuration
      *  
      *  @return \Faker\Components\Engine\Common\Builder\AlphaNumericTypeDefinition
      *  @access public   
      */
    public function fieldAlphaNumeric();
    
    /**
      *  Return an  field for configuration
      *  
      *  @return
      *  @access public   
      */
    public function fieldAutoIncrement();
    
    /**
      * Return an  field for configuration
      * 
      *  @return
      *  @access public   
      */
    public function fieldBoolean();
    
    /**
      *  Return an  field for configuration
      *
      *  @return
      *  @access public
      */
    public function fieldCity();
    
    /**
      *  Return an  field for configuration
      *
      *  @return
      *  @access public   
      */
    public function fieldConstant();
    
    /**
      *  Return an  field for configuration
      *
      *  @return
      *  @access public
      */
    public function fieldcountry();
    
    /**
      * Return an  field for configuration
      * 
      *  @return
      *  @access public
      */
    public function fieldDate();
    
    /**
      * Return an  field for configuration
      *
      *  @return
      *  @access public
      */
    public function fieldEmail();
    
    /**
      * Return an  field for configuration
      *
      *  @return
      *  @access public
      */
    public function fieldPeopleName();
    
    /**
      * Return an  field for configuration
      *
      *  @return
      *  @access public
      */
    public function fieldNull();
    
    /**
      * Return an  field for configuration
      *  
      *  @return
      *  @access public
      */
    public function fieldNumeric();
    
    /**
     *  Return an  field for configuration
     *
     *  @return
     *  @access public   
     */
    public function fieldRange();
    
    /**
      *  Return an  field for configuration
      * 
      *  @return
      *  @access public   
      */
    public function fieldRegex();
    
    /**
      * Return an  field for configuration
      * 
      * @return
      * @access public
      */
    public function fieldTemplate();
    
    /**
      * Return an  field for configuration
      *
      *  @return
      *  @access public   
      */
    public function fieldText();
    
    /**
      *  Return an  field for configuration
      *
      *  @return
      *  @access public   
      */
    public function fieldUniqueNumber();
    
    /**
      *  Return an  field for configuration
      *  
      *  @return
      *  @access public
      */
    public function fieldUniqueString();

    /**
      *  Returns the custom type for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\AbstractDefinition
      *  @access public
      */
    public function fieldCustom($alias);
    
    /**
      *  Return a closure type for configuration
      *
      *  @access public
      *  @return \Faker\Components\Engine\Common\Builder\ClosureTypeDefinition
      */
    public function fieldClosure();

}

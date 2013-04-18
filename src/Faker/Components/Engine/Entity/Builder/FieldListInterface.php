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
      *  @return Faker\Components\Engine\Common\Builder\AlphaNumericTypeDefinition
      *  @access public   
      */
    public function fieldAlphaNumeric();
    
    /**
      *  Return an  field for configuration
      *  
      *  @return \Faker\Components\Engine\Common\Builder\AutoIncrementTypeDefinition
      *  @access public   
      */
    public function fieldAutoIncrement();
    
    /**
      * Return an boolean field for configuration
      * 
      *  @return \Faker\Components\Engine\Common\Builder\BooleanTypeDefinition
      *  @access public   
      */
    public function fieldBoolean();
    
    /**
      *  Return an City field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\CitiesTypeDefinition
      *  @access public
      */
    public function fieldCity();
    
    /**
      *  Return an Constant field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\ConstantNumberTypeDefinition 
      *  @access public   
      */
    public function fieldConstant();
    
    /**
      *  Return an Country field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\CountryTypeDefinition
      *  @access public
      */
    public function fieldCountry();
    
    /**
      * Return an Date field for configuration
      * 
      *  @return \Faker\Components\Engine\Common\Builder\DateTypeDefinition
      *  @access public
      */
    public function fieldDate();
    
    /**
      * Return an Email field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\EmailTypeDefinition
      *  @access public
      */
    public function fieldEmail();
    
    /**
      * Return a People field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\NamesTypeDefinition
      *  @access public
      */
    public function fieldPeople();
    
    /**
      * Return a Null field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\NullTypeDefinition
      *  @access public
      */
    public function fieldNull();
    
    /**
      * Return a Numeric field for configuration
      *  
      *  @return \Faker\Components\Engine\Common\Builder\NumericTypeDefinition
      *  @access public
      */
    public function fieldNumeric();
    
    /**
     *  Return an Range Definition
     *
     *  @return \Faker\Components\Engine\Common\Builder\RangeTypeDefinition
     *  @access public   
     */
    public function fieldRange();
    
    /**
      *  Return a Regex field for configuration
      * 
      *  @return \Faker\Components\Engine\Common\Builder\RegexTypeDefinition
      *  @access public   
      */
    public function fieldRegex();
    
    /**
      * Return a Template field for configuration
      * 
      * @return \Faker\Components\Engine\Common\Builder\TemplateTypeDefinition
      * @access public
      */
    public function fieldTemplate();
    
    /**
      * Return a Text field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\TextTypeDefinition
      *  @access public   
      */
    public function fieldText();
    
    /**
      *  Return a Unique Number  field for configuration
      *
      *  @return \Faker\Components\Engine\Common\Builder\UniqueNumberTypeDefinition
      *  @access public   
      */
    public function fieldUniqueNumber();
    
    /**
      *  Return a Unique String field for configuration
      *  
      *  @return \Faker\Components\Engine\Common\Builder\UniqueStringTypeDefinition
      *  @access public
      */
    public function fieldUniqueString();

    
    /**
      *  Return a closure type for configuration
      *
      *  @access public
      *  @return \Faker\Components\Engine\Common\Builder\ClosureTypeDefinition
      */
    public function fieldClosure();

}

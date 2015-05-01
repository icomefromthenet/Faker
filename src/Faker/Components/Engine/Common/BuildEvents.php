<?php
namespace Faker\Components\Engine\Common;

/**
  *  List of events the buidler will fire
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.3
  */
final class BuildEvents
{
    /**
      *  Fired when the build process starts
      *
      *  @var string
      */
    const onBuildingStart = 'builder.start';
    
    /**
      *  Fired when build process has finished
      *
      *  @var string
      */
    const onBuildingEnd   = 'builder.end';
    
    /**
      *   Fired when validation starts
      *
      *   @var string
      */    
    const onValidationStart = 'builder.validation.start';
    
    /**
      *  Fired when validation ends
      *
      *  @var string
      */
    const onValidationEnd  = 'builder.validation.end';
    
    /**
      * Fired when compiler starts
      *
      * @var string
      */
    const onCompileStart   = 'builder.compiler.start';
    
    /**
      *  Fired when compiler stops
      *
      *  @var string
      */    
    const onCompileEnd     = 'builder.compiler.end';
   
   /**
    * Return the events contained in this class 
    * 
    * @return array[string] array of event names
    * @access public
    */
    public static function getEvents()
    {
        return array(
           self::onBuildingStart
           ,self::onBuildingEnd
           ,self::onValidationStart
           ,self::onValidationEnd
           ,self::onCompileStart
           ,self::onCompileEnd
        );
        
    }
    
}
/* End of File */
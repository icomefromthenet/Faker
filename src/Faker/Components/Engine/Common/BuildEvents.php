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
   
    
}
/* End of File */
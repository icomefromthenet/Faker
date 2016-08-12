<?php
namespace Faker\Tests\Engine\Common\Formatter\Mock;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Faker\Components\Engine\Common\Formatter\BaseFormatter;
use Faker\Components\Engine\Common\Formatter\FormatterInterface;
use Faker\Components\Engine\Common\Formatter\GenerateEvent;

class MockFormatter extends BaseFormatter implements FormatterInterface
{
    
    
    public function getName()
    {
        return 'mockFormatter';
    }
    
    public function toXml()
    {
        return '';
    }
    
    public function getConfigExtension(ArrayNodeDefinition $rootNode)
    {
        return $rootNode;
    }
    
    public function getOuputFileFormat()
    {
        return '{prefix}_{body}_{suffix}_{seq}.{ext}';
    }
    
    public function getDefaultOutEncoding()
    {
        return 'UTF-8';
    }
    
    
      /**
      *  Handles Event FormatEvents::onSchemaStart
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaStart(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onSchemaEnd
      *
      *  @param GenerateEvent $event
      */
    public function onSchemaEnd(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableStart
      *
      *  @param GenerateEvent $event
      */
    public function onTableStart(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onTableEnd
      *
      *  @param GenerateEvent $event
      */
    public function onTableEnd(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowStart
      *
      *  @param GenerateEvent $event
      */
    public function onRowStart(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onRowEnd
      *
      *  @param GenerateEvent $event
      */
    public function onRowEnd(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onColumnStart
      *
      *  @param GenerateEvent $event
      */
    public function onColumnStart(GenerateEvent $event)
    {
        
    }
    
    /**
      *  Handles Event FormatEvents::onColumnGenerate
      *
      *  @param GenerateEvent $event
      */
    public function onColumnGenerate(GenerateEvent $event)
    {
        
    }
    
    
    /**
      *  Handles Event FormatEvents::onColumnEnd
      *
      *  @param GenerateEvent $event
      */
    public function onColumnEnd(GenerateEvent $event)
    {
        
    }
    
};
/* End of File */
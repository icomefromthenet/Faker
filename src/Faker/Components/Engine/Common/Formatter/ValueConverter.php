<?php
namespace Faker\Components\Engine\Common\Formatter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Faker\Components\Engine\EngineException;

/**
  *  Provides a map off columns to DBALTypes and a conversion routine. Columns are referenced
  *  by their name.
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
class ValueConverter extends ArrayCollection
{
    
    
    public function convertValue($key,AbstractPlatform $platform,$value)
    {
        if($this->containsKey($key) === false) {
            throw new EngineException('Unknown column mapping at key::'.$key);
        }

        $type =     $this->get($key);
        
        return $type->convertToDatabaseValue($value,$platform);
    }
    
}
/* End of File */
<?php
namespace Faker\Components\Faker;

use Faker\Components\Faker\GeneratorCache;

/*
 * interface CacheInterface
 *
 * When composite must cache generated values or member of a foreign key relation
 * it must implement this interface
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.2
 */

interface CacheInterface 
{
    
    /**
      *  Set the composite with a cache
      *
      *  @param GeneratorCache $cache
      *  @access public 
      */
    public function setGeneratorCache(GeneratorCache $cache);
    
    
    /**
      *  Fetch the assigned generator Cache
      *
      *  @access public
      *  @return GeneratorCache
      */
    public function getGeneratorCache();
    
    
    /**
      *  Tell the compiste to cache values or retrieve from cache
      *
      *  @param boolean $bool
      *  @access public
      */
    public function setUseCache($bool);
    
    
    /**
      *  Check if a cache should be used
      *
      *  @return boolean true to use cache
      *  @access public
      */
    public function getUseCache();
    
}
/* End of File */
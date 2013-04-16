<?php
namespace Faker\Components\Engine\Common\Builder;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Faker\Components\Engine\Common\Utilities;
use Faker\Locale\LocaleInterface;
use Faker\Components\Templating\Loader;
use PHPStats\Generator\GeneratorInterface;
use Doctrine\DBAL\Connection;

/**
  *  Methods for Definitions that represent Types and Selectors
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 1.0.4
  */
interface TypeDefinitionInterface
{

    public function locale(LocaleInterface $locale);

    
    public function generator(GeneratorInterface $gen);

    
    public function utilities(Utilities $util);
    

    public function eventDispatcher(EventDispatcherInterface $dispatcher);
    
    
    public function database(Connection $conn);
    
    
    public function templateLoader(Loader $template);
    
    
    /**
    * Sets an attribute on the node.
    *
    * @param string $key
    * @param mixed $value
    *
    * @return AbstractDefinition
    */
    public function attribute($key, $value);
    
    
}
/* End of File */
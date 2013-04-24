<?php
namespace Faker\Tests\Engine\Common\Formatter\Mock;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Faker\Components\Engine\Common\Formatter\BaseFormatter;


class MockFormatter extends BaseFormatter
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
    
};
/* End of File */
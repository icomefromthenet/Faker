<?php
namespace Faker\Components\Engine\Original\Compiler\Pass;

use Faker\Components\Engine\Original\Exception as FakerException;

/**
 * This exception is thrown when a circular reference is found by the compiler pass.
 *
 * @author Lewis Dyer <getintouch@icomefromthenet.com>
 * @since 1.0.3
 */
class CircularReferenceException extends FakerException
{
    private $service_id;
    private $path;

    public function __construct($service_id, array $path)
    {
        parent::__construct(sprintf('Circular reference detected "%s", path: "%s".', $service_id, implode(' -> ', $path)));

        $this->service_id = $service_id;
        $this->path       = $path;
    }

    public function getServiceId()
    {
        return $this->service_id;
    }

    public function getPath()
    {
        return $this->path;
    }
}
/* End of File */
<?php
namespace Faker\Tests\Config;

use Faker\Project,
    Faker\Io\Io,
    Faker\Components\Config\Entity,
    Faker\Components\Config\Writer,
    Faker\Components\Config\Loader,
    Faker\Tests\Base\AbstractProject;

class ComponentTest extends AbstractProject
{

    public function testManagerLoader()
    {
        $project = $this->getProject();

        $manager = $project['config_manager'];

        $this->assertInstanceOf('Faker\Components\Config\Manager',$manager);

        # check that only one instances is created
        $manager2 =  $project['config_manager'];

        $this->assertSame($manager,$manager2);

    }


    public function testManagerGetLoader()
    {
        $project = $this->getProject();
        $manager = $project['config_manager'];

        $loader = $manager->getLoader();

        $this->assertInstanceOf('Faker\Components\Config\Loader',$loader);

        return $loader;
    }

    public function testManagerGetWriter()
    {
        $project = $this->getProject();
        $manager = $project['config_manager'];

        $writer = $manager->getWriter();

        $this->assertInstanceOf('Faker\Components\Config\Writer',$writer);

        return $writer;
    }

}
/* End of File */

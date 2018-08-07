<?php
namespace Faker\Tests\Templating;

use Faker\Project,
    Faker\Io\Io,
    Faker\Components\Templating\Entity,
    Faker\Components\Templating\Writer,
    Faker\Components\Templating\Loader,
    Faker\Components\Templating\TwigLoader,
    Faker\Components\Templating\Io as TemplateIO,
    Faker\Components\Templating\Template,
    Faker\Tests\Base\AbstractProject;  

class TemplateTwigLoaderTest extends AbstractProject
{
     

    public function testTwigLoader()
    {
        $loader  = new TwigLoader(new TemplateIo($this->getProject()->getPath()->get()));

        $this->assertInstanceOf('\Faker\Components\Templating\TwigLoader',$loader);

        # test isfresh returns true (always fresh)
        $this->assertTrue($loader->isFresh('file',100));

        # test if the cache key hands back the argument unchanged (no cache)
        $this->assertSame('one',$loader->getCacheKey('one'));

        # make sure io properties work
        $this->assertInstanceOf('Faker\Components\Templating\Io',$loader->getIo());

        # test template load for valid file
        $template = $loader->getSourceContext('sql/mysql/header_template.twig');

        $this->assertNotEmpty($template);
    }

   


}
/* End of File */

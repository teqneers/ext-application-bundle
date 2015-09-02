<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 02.09.15
 * Time: 12:16
 */

namespace TQ\Bundle\ExtJSApplicationBundle\Tests\Manifest;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use TQ\Bundle\ExtJSApplicationBundle\Manifest\UrlPathMapper;

/**
 * Class UrlPathMapperTest
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\Tests\Manifest
 */
class UrlPathMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testAbsolutePathInProduction()
    {
        /** @var UrlGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject $urlGenerator */
        $urlGenerator = $this->getMock(
            'Symfony\Component\Routing\Generator\UrlGeneratorInterface',
            array('generate', 'setContext', 'getContext')
        );

        $urlGenerator->expects($this->never())
                     ->method('generate');

        $pathMapper = new UrlPathMapper($urlGenerator);

        $this->assertEquals('/my/absolute/path', $pathMapper->mapPath('/my/absolute/path', 'desktop', false));
    }

    public function testAbsolutePathInDevelopment()
    {
        /** @var UrlGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject $urlGenerator */
        $urlGenerator = $this->getMock(
            'Symfony\Component\Routing\Generator\UrlGeneratorInterface',
            array('generate', 'setContext', 'getContext')
        );

        $urlGenerator->expects($this->never())
                     ->method('generate');

        $pathMapper = new UrlPathMapper($urlGenerator);

        $this->assertEquals('/my/absolute/path', $pathMapper->mapPath('/my/absolute/path', 'desktop', true));
    }

    public function testRelativePathInProduction()
    {
        /** @var UrlGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject $urlGenerator */
        $urlGenerator = $this->getMock(
            'Symfony\Component\Routing\Generator\UrlGeneratorInterface',
            array('generate', 'setContext', 'getContext')
        );

        $urlGenerator->expects($this->once())
                     ->method('generate')
                     ->with(
                         $this->equalTo('tq_extjs_application_resources'),
                         $this->equalTo([
                             'build' => 'desktop',
                             'dev'   => '',
                             'path'  => 'my/relative/path'
                         ])
                     )
                     ->willReturn('url');

        $pathMapper = new UrlPathMapper($urlGenerator);

        $this->assertEquals('url', $pathMapper->mapPath('my/relative/path', 'desktop', false));
    }

    public function testRelativePathInDevelopment()
    {
        /** @var UrlGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject $urlGenerator */
        $urlGenerator = $this->getMock(
            'Symfony\Component\Routing\Generator\UrlGeneratorInterface',
            array('generate', 'setContext', 'getContext')
        );

        $urlGenerator->expects($this->once())
                     ->method('generate')
                     ->with(
                         $this->equalTo('tq_extjs_application_resources'),
                         $this->equalTo([
                             'build' => 'desktop',
                             'dev'   => '-dev',
                             'path'  => 'my/relative/path'
                         ])
                     )
                     ->willReturn('url');

        $pathMapper = new UrlPathMapper($urlGenerator);

        $this->assertEquals('url', $pathMapper->mapPath('my/relative/path', 'desktop', true));
    }
}

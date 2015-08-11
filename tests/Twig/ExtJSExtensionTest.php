<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 15:32
 */

namespace TQ\Bundle\ExtJSApplicationBundle\Tests\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use TQ\Bundle\ExtJSApplicationBundle\Twig\ExtJSExtension;
use TQ\ExtJS\Application\Application;

/**
 * Class ExtJSExtensionTest
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\Tests\Twig
 */
class ExtJSExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function testGetManifestPath()
    {
        /** @var Application|\PHPUnit_Framework_MockObject_MockObject $application */
        $application = $this->getMock(
            'TQ\ExtJS\Application\Application',
            array('getDefaultBuild'),
            array(),
            '',
            false
        );

        $application->expects($this->once())
                    ->method('getDefaultBuild')
                    ->willReturn('desktop');

        /** @var UrlGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject $urlGenerator */
        $urlGenerator = $this->getMock(
            'Symfony\Component\Routing\Generator\UrlGeneratorInterface',
            array('generate', 'setContext', 'getContext')
        );

        $urlGenerator->expects($this->once())
                     ->method('generate')
                     ->with(
                         $this->equalTo('tq_extjs_application_manifest'),
                         $this->equalTo([
                             'build' => 'desktop'
                         ])
                     )
                     ->willReturn('url');

        $extension = new ExtJSExtension($urlGenerator, $application);
        $this->assertEquals('url', $extension->getManifestPath());
    }


    public function testBootstrapPath()
    {
        /** @var Application|\PHPUnit_Framework_MockObject_MockObject $application */
        $application = $this->getMock(
            'TQ\ExtJS\Application\Application',
            array('getDefaultBuild'),
            array(),
            '',
            false
        );

        $application->expects($this->once())
                    ->method('getDefaultBuild')
                    ->willReturn('desktop');

        /** @var UrlGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject $urlGenerator */
        $urlGenerator = $this->getMock(
            'Symfony\Component\Routing\Generator\UrlGeneratorInterface',
            array('generate', 'setContext', 'getContext')
        );

        $urlGenerator->expects($this->once())
                     ->method('generate')
                     ->with(
                         $this->equalTo('tq_extjs_application_bootstrap'),
                         $this->equalTo([
                             'build' => 'desktop'
                         ])
                     )
                     ->willReturn('url');

        $extension = new ExtJSExtension($urlGenerator, $application);
        $this->assertEquals('url', $extension->getBootstrapPath());
    }
}

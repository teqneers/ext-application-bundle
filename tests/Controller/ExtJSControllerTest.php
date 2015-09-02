<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 16:30
 */

namespace TQ\Bundle\ExtJSApplicationBundle\Tests\Controller;


use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use TQ\Bundle\ExtJSApplicationBundle\Controller\ExtJSController;
use TQ\ExtJS\Application\Application;
use TQ\ExtJS\Application\Exception\FileNotFoundException;
use TQ\ExtJS\Application\Manifest\Manifest;

/**
 * Class ExtJSControllerTest
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\Tests\Controller
 */
class ExtJSControllerTest extends \PHPUnit_Framework_TestCase
{

    public function testBootstrapAction()
    {
        /** @var Application|\PHPUnit_Framework_MockObject_MockObject $application */
        $application = $this->getMock(
            'TQ\ExtJS\Application\Application',
            array('getMicroLoaderFile'),
            array(),
            '',
            false
        );

        $application->expects($this->once())
                    ->method('getMicroLoaderFile')
                    ->with(
                        $this->equalTo('desktop')
                    )
                    ->willReturn(new \SplFileInfo(__DIR__ . '/__files/bootstrap.js'));

        $controller = new ExtJSController($application);
        /** @var BinaryFileResponse $response */
        $response = $controller->bootstrapAction('desktop', new Request());

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\BinaryFileResponse', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(__DIR__ . '/__files/bootstrap.js', $response->getFile()
                                                                        ->getPathname());
        $this->assertEquals('application/javascript', $response->headers->get('Content-Type'));
    }

    public function testBootstrapActionFailsIfFileNotFound()
    {
        /** @var Application|\PHPUnit_Framework_MockObject_MockObject $application */
        $application = $this->getMock(
            'TQ\ExtJS\Application\Application',
            array('getMicroLoaderFile'),
            array(),
            '',
            false
        );

        $application->expects($this->once())
                    ->method('getMicroLoaderFile')
                    ->with(
                        $this->equalTo('desktop')
                    )
                    ->willThrowException(new FileNotFoundException('does-not-exist'));

        $controller = new ExtJSController($application);

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $controller->bootstrapAction('desktop', new Request());
    }

    public function testManifestAction()
    {
        /** @var Application|\PHPUnit_Framework_MockObject_MockObject $application */
        $application = $this->getMock(
            'TQ\ExtJS\Application\Application',
            array('getManifest'),
            array(),
            '',
            false
        );

        $request = new Request();

        $application->expects($this->once())
                    ->method('getManifest')
                    ->with(
                        $this->equalTo('desktop')
                    )
                    ->willReturn(new Manifest(__DIR__ . '/__files/manifest.json', array()));

        $controller = new ExtJSController($application);
        /** @var StreamedResponse $response */
        $response = $controller->manifestAction('desktop', $request);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\StreamedResponse', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testManifestActionFailsIfFileNotFound()
    {
        /** @var Application|\PHPUnit_Framework_MockObject_MockObject $application */
        $application = $this->getMock(
            'TQ\ExtJS\Application\Application',
            array('getManifest'),
            array(),
            '',
            false
        );

        $request = new Request();

        $application->expects($this->once())
                    ->method('getManifest')
                    ->with(
                        $this->equalTo('desktop')
                    )
                    ->willThrowException(new FileNotFoundException('does-not-exist'));

        $controller = new ExtJSController($application);

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $controller->manifestAction('desktop', $request);
    }

    public function testAppCacheAction()
    {
        /** @var Application|\PHPUnit_Framework_MockObject_MockObject $application */
        $application = $this->getMock(
            'TQ\ExtJS\Application\Application',
            array('getAppCacheFile'),
            array(),
            '',
            false
        );

        $application->expects($this->once())
                    ->method('getAppCacheFile')
                    ->with(
                        $this->equalTo('desktop')
                    )
                    ->willReturn(new \SplFileInfo(__DIR__ . '/__files/cache.appcache'));

        $controller = new ExtJSController($application);
        /** @var BinaryFileResponse $response */
        $response = $controller->appCacheAction('desktop', new Request());

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\BinaryFileResponse', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(__DIR__ . '/__files/cache.appcache', $response->getFile()
                                                                          ->getPathname());
        $this->assertEquals('text/cache-manifest', $response->headers->get('Content-Type'));
    }

    public function testAppCacheActionFailsIfFileNotFound()
    {
        /** @var Application|\PHPUnit_Framework_MockObject_MockObject $application */
        $application = $this->getMock(
            'TQ\ExtJS\Application\Application',
            array('getAppCacheFile'),
            array(),
            '',
            false
        );

        $application->expects($this->once())
                    ->method('getAppCacheFile')
                    ->with(
                        $this->equalTo('desktop')
                    )
                    ->willThrowException(new FileNotFoundException('does-not-exist'));

        $controller = new ExtJSController($application);

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $controller->appCacheAction('desktop', new Request());
    }
}

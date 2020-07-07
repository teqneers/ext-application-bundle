<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 16:51
 */

namespace TQ\Bundle\ExtJSApplicationBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Kernel;
use TQ\Bundle\ExtJSApplicationBundle\Controller\ExtJSController;
use TQ\Bundle\ExtJSApplicationBundle\TQExtJSApplicationBundle;

/**
 * Class TQExtJSApplicationBundleTest
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\Tests
 */
class TQExtJSApplicationBundleTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->clearTempDir();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        $this->clearTempDir();
    }

    public function testBootstrapActionProduction()
    {
        $kernel = new AppKernel('prod', false);
        $kernel->boot();

        /** @var ExtJSController $controller */
        $controller = $kernel->getContainer()
            ->get('tq_extjs.controller');
        $request = new Request();
        /** @var BinaryFileResponse $response */
        $response = $controller->bootstrapAction('desktop', new Request());

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\BinaryFileResponse', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            __DIR__ . '/__files/ExampleApp/build/production/ExampleApp/microloader.js',
            $response->getFile()
                ->getPathname()
        );
        $this->assertEquals('application/javascript', $response->headers->get('Content-Type'));

        $this->expectOutputString(file_get_contents(__DIR__ . '/__files/ExampleApp/build/production/ExampleApp/microloader.js'));
        $response->prepare($request);
        $response->sendContent();
    }

    public function testManifestActionProduction()
    {
        $kernel = new AppKernel('prod', false);
        $kernel->boot();

        /** @var ExtJSController $controller */
        $controller = $kernel->getContainer()
            ->get('tq_extjs.controller');
        $request = new Request();
        /** @var StreamedResponse $response */
        $response = $controller->manifestAction('desktop', $request);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\StreamedResponse', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));

        $this->expectOutputString(
            json_encode(
                json_decode(
                    file_get_contents(__DIR__ . '/__files/ExampleApp/build/production/ExampleApp/app.expected.json'),
                    true
                )
            )
        );
        $response->prepare($request);
        $response->sendContent();
    }

    public function testBootstrapActionDevelopment()
    {
        $kernel = new AppKernel('dev', false);
        $kernel->boot();

        /** @var ExtJSController $controller */
        $controller = $kernel->getContainer()
            ->get('tq_extjs.controller');
        $request = new Request();
        /** @var BinaryFileResponse $response */
        $response = $controller->bootstrapAction('desktop', new Request());

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\BinaryFileResponse', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            __DIR__ . '/__files/ExampleApp/bootstrap.js',
            $response->getFile()
                ->getPathname()
        );
        $this->assertEquals('application/javascript', $response->headers->get('Content-Type'));

        $this->expectOutputString(file_get_contents(__DIR__ . '/__files/ExampleApp/bootstrap.js'));
        $response->prepare($request);
        $response->sendContent();
    }

    public function testManifestActionDevelopment()
    {
        $kernel = new AppKernel('dev', false);
        $kernel->boot();

        /** @var ExtJSController $controller */
        $controller = $kernel->getContainer()
            ->get('tq_extjs.controller');
        $request = new Request();
        /** @var StreamedResponse $response */
        $response = $controller->manifestAction('desktop', $request);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\StreamedResponse', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));

        $this->expectOutputString(
            json_encode(
                json_decode(
                    file_get_contents(__DIR__ . '/__files/ExampleApp/bootstrap.expected.json'),
                    true
                )
            )
        );
        $response->prepare($request);
        $response->sendContent();
    }

    public function testAppCacheActionProduction()
    {
        $kernel = new AppKernel('prod', false);
        $kernel->boot();

        /** @var ExtJSController $controller */
        $controller = $kernel->getContainer()
            ->get('tq_extjs.controller');
        $request = new Request();
        /** @var BinaryFileResponse $response */
        $response = $controller->appCacheAction('desktop', new Request());

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\BinaryFileResponse', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            __DIR__ . '/__files/ExampleApp/build/production/ExampleApp/cache.appcache',
            $response->getFile()
                ->getPathname()
        );
        $this->assertEquals('text/cache-manifest', $response->headers->get('Content-Type'));

        $this->expectOutputString(file_get_contents(__DIR__ . '/__files/ExampleApp/build/production/ExampleApp/cache.appcache'));
        $response->prepare($request);
        $response->sendContent();
    }

    public function testAppCacheActionDevelopment()
    {
        $kernel = new AppKernel('dev', false);
        $kernel->boot();

        /** @var ExtJSController $controller */
        $controller = $kernel->getContainer()
            ->get('tq_extjs.controller');
        /** @var BinaryFileResponse $response */
        $this->expectException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $controller->appCacheAction('desktop', new Request());
    }

    protected function clearTempDir()
    {
        $dir = sys_get_temp_dir() . '/ext-application-bundle';
        if (is_dir($dir)) {
            foreach (
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator(
                        $dir,
                        \RecursiveDirectoryIterator::SKIP_DOTS
                    ),
                    \RecursiveIteratorIterator::CHILD_FIRST
                ) as $file
            ) {
                /** @var \SplFileInfo $file */
                if ($file->isDir()) {
                    @rmdir($file->getPathname());
                } else {
                    @unlink($file->getPathName());
                }
            }
            @rmdir($dir);
        }
    }
}

class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return array(
            new FrameworkBundle(),
            new TQExtJSApplicationBundle(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/TQExtJSApplicationBundleTestConfig.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return sys_get_temp_dir() . '/ext-application-bundle/cache';
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return sys_get_temp_dir() . '/ext-application-bundle/log';
    }
}

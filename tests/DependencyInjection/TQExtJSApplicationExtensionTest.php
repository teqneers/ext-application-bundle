<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 15:58
 */

namespace TQ\Bundle\ExtJSApplicationBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use TQ\Bundle\ExtJSApplicationBundle\TQExtJSApplicationBundle;
use TQ\ExtJS\Application\Application;

/**
 * Class TQExtJSApplicationExtensionTest
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\Tests\DependencyInjection
 */
class TQExtJSApplicationExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->clearTempDir();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->clearTempDir();
    }

    public function testLoadDebugDevelopment()
    {
        $container = $this->getContainerForConfig(array(
            array(
                'builds' => array(
                    'desktop' => array(
                        'development_base' => 'my-app',
                        'production_base'  => 'app'
                    )
                )
            )
        ), 'dev', true);

        /** @var Application $application */
        $application = $container->get('tq_extjs.application');
        $this->assertInstanceOf(
            'TQ\ExtJS\Application\Application',
            $application
        );
        $this->assertEquals(
            sys_get_temp_dir() . '/ext-application-bundle/app/../workspace/my-app',
            $application->getBasePath()
        );
    }

    public function testLoadDebugProduction()
    {
        $container = $this->getContainerForConfig(array(
            array(
                'builds' => array(
                    'desktop' => array(
                        'development_base' => 'my-app',
                        'production_base'  => 'app'
                    )
                )
            )
        ), 'prod', true);

        /** @var Application $application */
        $application = $container->get('tq_extjs.application');
        $this->assertInstanceOf(
            'TQ\ExtJS\Application\Application',
            $application
        );
        $this->assertEquals(
            sys_get_temp_dir() . '/ext-application-bundle/app/../web/app',
            $application->getBasePath()
        );
    }

    public function testLoadDevelopment()
    {
        $container = $this->getContainerForConfig(array(
            array(
                'builds' => array(
                    'desktop' => array(
                        'development_base' => 'my-app',
                        'production_base'  => 'app'
                    )
                )
            )
        ), 'dev', false);

        /** @var Application $application */
        $application = $container->get('tq_extjs.application');
        $this->assertInstanceOf(
            'TQ\ExtJS\Application\Application',
            $application
        );
        $this->assertEquals(
            sys_get_temp_dir() . '/ext-application-bundle/app/../workspace/my-app',
            $application->getBasePath()
        );
    }

    public function testLoadProduction()
    {
        $container = $this->getContainerForConfig(array(
            array(
                'builds' => array(
                    'desktop' => array(
                        'development_base' => 'my-app',
                        'production_base'  => 'app'
                    )
                )
            )
        ), 'prod', false);

        /** @var Application $application */
        $application = $container->get('tq_extjs.application');
        $this->assertInstanceOf(
            'TQ\ExtJS\Application\Application',
            $application
        );
        $this->assertEquals(
            sys_get_temp_dir() . '/ext-application-bundle/app/../web/app',
            $application->getBasePath()
        );
    }

    /**
     * @param array  $configs
     * @param string $environment
     * @param bool   $debug
     * @return ContainerBuilder
     */
    protected function getContainerForConfig(array $configs, $environment, $debug)
    {
        $rootPath = sys_get_temp_dir() . '/ext-application-bundle';

        /** @var UrlGeneratorInterface */
        $urlGenerator = $this->getMock(
            'Symfony\Component\Routing\Generator\UrlGeneratorInterface',
            array('generate', 'setContext', 'getContext')
        );

        $bundle    = new TQExtJSApplicationBundle();
        $extension = $bundle->getContainerExtension();
        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', $debug);
        $container->setParameter('kernel.environment', $environment);
        $container->setParameter('kernel.root_dir', $rootPath . '/app');
        $container->setParameter('kernel.cache_dir', $rootPath . '/cache');
        $container->setParameter('kernel.bundles', array());
        $container->set('router', $urlGenerator);
        $container->set('service_container', $container);
        $container->registerExtension($extension);
        $extension->load($configs, $container);
        $bundle->build($container);
        $container->compile();
        return $container;
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

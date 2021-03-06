<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 15:58
 */

namespace TQ\Bundle\ExtJSApplicationBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use TQ\Bundle\ExtJSApplicationBundle\TQExtJSApplicationBundle;
use TQ\ExtJS\Application\Application;

/**
 * Class TQExtJSApplicationExtensionTest
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\Tests\DependencyInjection
 */
class TQExtJSApplicationExtensionTest extends TestCase
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

    public function testLoadDebugDevelopment()
    {
        $container = $this->getContainerForConfig(array(
            array(
                'app_path' => '%kernel.project_dir%/ExampleApp',
                'builds' => array(
                    'desktop' => array(
                        'development' => array(
                            'build_path' => 'build/development/ExampleApp'
                        ),
                        'production' => array(
                            'build_path' => 'build/production/ExampleApp'
                        )
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
            sys_get_temp_dir() . '/ext-application-bundle/ExampleApp/build/development/ExampleApp',
            $application->getBuildPath()
        );
    }

    public function testLoadDebugProduction()
    {
        $container = $this->getContainerForConfig(array(
            array(
                'app_path' => '%kernel.project_dir%/ExampleApp',
                'builds' => array(
                    'desktop' => array(
                        'development' => array(
                            'build_path' => 'build/development/ExampleApp'
                        ),
                        'production' => array(
                            'build_path' => 'build/production/ExampleApp'
                        )
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
            sys_get_temp_dir() . '/ext-application-bundle/ExampleApp/build/production/ExampleApp',
            $application->getBuildPath()
        );
    }

    public function testLoadDevelopment()
    {
        $container = $this->getContainerForConfig(array(
            array(
                'app_path' => '%kernel.project_dir%/ExampleApp',
                'builds' => array(
                    'desktop' => array(
                        'development' => array(
                            'build_path' => 'build/development/ExampleApp'
                        ),
                        'production' => array(
                            'build_path' => 'build/production/ExampleApp'
                        )
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
            sys_get_temp_dir() . '/ext-application-bundle/ExampleApp/build/development/ExampleApp',
            $application->getBuildPath()
        );
    }

    public function testLoadProduction()
    {
        $container = $this->getContainerForConfig(array(
            array(
                'app_path' => '%kernel.project_dir%/ExampleApp',
                'builds' => array(
                    'desktop' => array(
                        'development' => array(
                            'build_path' => 'build/development/ExampleApp'
                        ),
                        'production' => array(
                            'build_path' => 'build/production/ExampleApp'
                        )
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
            sys_get_temp_dir() . '/ext-application-bundle/ExampleApp/build/production/ExampleApp',
            $application->getBuildPath()
        );
    }

    /**
     * @param array $configs
     * @param string $environment
     * @param bool $debug
     * @return ContainerBuilder
     */
    protected function getContainerForConfig(array $configs, $environment, $debug)
    {
        $rootPath = sys_get_temp_dir() . '/ext-application-bundle';

        /** @var UrlGeneratorInterface */
        $urlGenerator = $this->createPartialMock(
            'Symfony\Component\Routing\Generator\UrlGeneratorInterface',
            array('generate', 'setContext', 'getContext')
        );

        $bundle = new TQExtJSApplicationBundle();
        $extension = $bundle->getContainerExtension();
        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', $debug);
        $container->setParameter('kernel.environment', $environment);
        $container->setParameter('kernel.project_dir', $rootPath);
        $container->setParameter('kernel.cache_dir', $rootPath . '/cache');
        $container->setParameter('kernel.bundles', array());
        $container->set('router', $urlGenerator);
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

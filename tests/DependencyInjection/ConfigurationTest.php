<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 15:48
 */

namespace TQ\Bundle\ExtJSApplicationBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use TQ\Bundle\ExtJSApplicationBundle\DependencyInjection\Configuration;

/**
 * Class ConfigurationTest
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\Tests\DependencyInjection
 */
class ConfigurationTest extends TestCase
{
    /**
     * @return Configuration
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }

    public function testConfigurationDefaults()
    {
        $configuration = $this->getConfiguration();
        $processor     = new Processor();
        $config        = $processor->processConfiguration($configuration, array(
            array(
                'app_path' => '%kernel.root_dir%/../ExampleApp',
                'builds'   => array(
                    'desktop' => array(
                        'development' => array(
                            'build_path' => 'build/development/ExampleApp'
                        ),
                        'production'  => array(
                            'build_path' => 'build/production/ExampleApp'
                        )
                    )
                )
            )
        ));

        $this->assertEquals(
            array(
                'app_path' => '%kernel.root_dir%/../ExampleApp',
                'builds'   => array(
                    'desktop' => array(
                        'development' => array(
                            'build_path'  => 'build/development/ExampleApp',
                            'microloader' => '/bootstrap.js',
                            'manifest'    => '/bootstrap.json',
                            'app_cache'   => null
                        ),
                        'production'  => array(
                            'build_path'  => 'build/production/ExampleApp',
                            'microloader' => 'microloader.js',
                            'manifest'    => 'app.json',
                            'app_cache'   => 'cache.appcache'
                        )
                    )
                )
            ),
            $config
        );
    }

    public function testConfigurationInvalidIfAppPathIsMissing()
    {
        $configuration = $this->getConfiguration();
        $processor     = new Processor();

        $this->expectException(
            'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException',
            'The child node "app_path" at path "tq_ext_js_application" must be configured.'
        );

        $processor->processConfiguration($configuration, array(array()));
    }

    public function testConfigurationInvalidIfBuildIsMissing()
    {
        $configuration = $this->getConfiguration();
        $processor     = new Processor();

        $this->expectException(
            'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException',
            'The child node "builds" at path "tq_ext_js_application" must be configured.'
        );

        $processor->processConfiguration($configuration, array(
            array(
                'app_path' => '%kernel.root_dir%/../ExampleApp'
            )
        ));
    }
}

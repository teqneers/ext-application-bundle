<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 22.07.15
 * Time: 15:48
 */

namespace TQ\Bundle\ExtJSApplicationBundle\Tests\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use TQ\Bundle\ExtJSApplicationBundle\DependencyInjection\Configuration;

/**
 * Class ConfigurationTest
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\Tests\DependencyInjection
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
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
                'builds' => array(
                    'desktop' => array(
                        'development_base' => 'my-app',
                        'production_base'  => 'app'
                    )
                )
            )
        ));

        $this->assertEquals(
            array(
                'workspace_path'          => '%kernel.root_dir%/../workspace',
                'relative_wWorkspace_url' => '../workspace',
                'web_path'                => '%kernel.root_dir%/../web',
                'relative_web_url'        => '/',
                'builds'                  => array(
                    'desktop' => array(
                        'development_base'        => 'my-app',
                        'production_base'         => 'app',
                        'development_manifest'    => 'manifest.json',
                        'development_microloader' => 'bootstrap.js',
                        'development_appcache'    => null,
                        'production_manifest'     => 'bootstrap.json',
                        'production_microloader'  => 'bootstrap.js',
                        'production_appcache'     => 'cache.appcache',
                    )
                )
            ),
            $config
        );
    }

    public function testConfigurationInvalidIfAppNameIsMissing()
    {
        $configuration = $this->getConfiguration();
        $processor     = new Processor();

        $this->setExpectedException(
            'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException',
            'The child node "builds" at path "tq_ext_js_application" must be configured.'
        );

        $processor->processConfiguration($configuration, array(array()));
    }
}

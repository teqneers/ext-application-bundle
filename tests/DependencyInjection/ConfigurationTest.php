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
                'app_name' => 'my-app'
            )
        ));

        $this->assertEquals(
            array(
                'app_name'       => 'my-app',
                'app_url'        => 'app',
                'workspace_url'  => '../workspace',
                'workspace_path' => '%kernel.root_dir%/../workspace',
                'web_path'       => '%kernel.root_dir%/../web',
                'bootstrap_name' => 'bootstrap.js',
                'manifest_name'  => 'manifest.json'
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
            'The child node "app_name" at path "tq_ext_js_application" must be configured.'
        );

        $processor->processConfiguration($configuration, array(array()));
    }
}

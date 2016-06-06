<?php
/**
 * teqneers/ext-application-bundle
 *
 * @category   TQ
 * @package    TQ\Bundle\ExtApplicationBundle
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\Bundle\ExtJSApplicationBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class TQExtJSApplicationExtension
 *
 * @package TQ\Bundle\ExtApplicationBundle\DependencyInjection
 */
class TQExtJSApplicationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('extjs_application.yml');

        $configuration = $this->getConfiguration($config, $container);
        $config        = $this->processConfiguration($configuration, $config);

        $container->setParameter('tq_extjs.app_path', $config['app_path']);

        $applicationDefinition = $container->getDefinition('tq_extjs.application_configuration');

        foreach ($config['builds'] as $name => $build) {
            $applicationDefinition->addMethodCall(
                'addBuild',
                [
                    $name,
                    $build['development'],
                    $build['production'],
                ]
            );
        }
    }
}

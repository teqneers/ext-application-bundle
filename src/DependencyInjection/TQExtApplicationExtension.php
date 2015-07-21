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
    public function getAlias()
    {
        return 'tq_extjs_application';
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('extjs_application.yml');

        $configuration = $this->getConfiguration($config, $container);
        $config        = $this->processConfiguration($configuration, $config);

        $applicationDefinition = $container->getDefinition('tq_extjs.application_configuration');
        $applicationDefinition->replaceArgument(0, $config['app_name']);
        $applicationDefinition->replaceArgument(1, $config['app_url']);
        $applicationDefinition->replaceArgument(2, $config['workspace_url']);
        $applicationDefinition->replaceArgument(3, $config['workspace_path']);
        $applicationDefinition->replaceArgument(4, $config['web_path']);
        $applicationDefinition->replaceArgument(5, $config['bootstrap_name']);
        $applicationDefinition->replaceArgument(6, $config['manifest_name']);
    }
}

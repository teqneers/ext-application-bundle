<?php
/**
 * teqneers/ext-application-bundle
 *
 * @category   TQ
 * @package    TQ\Bundle\ExtApplicationBundle
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\Bundle\ExtJSApplicationBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('tq_ext_js_application', 'array');

        $rootNode
            ->children()
                ->scalarNode('app_name')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('app_url')
                    ->defaultValue('app')
                ->end()
                ->scalarNode('workspace_url')
                    ->defaultValue('../workspace')
                ->end()
                ->scalarNode('workspace_path')
                    ->defaultValue('%kernel.root_dir%/../workspace')
                ->end()
                ->scalarNode('web_path')
                    ->defaultValue('%kernel.root_dir%/../web')
                ->end()
                ->scalarNode('bootstrap_name')
                    ->defaultValue('bootstrap.js')
                ->end()
                ->scalarNode('manifest_name')
                    ->defaultValue('manifest.json')
                ->end()
            ->end();

        return $treeBuilder;
    }
}

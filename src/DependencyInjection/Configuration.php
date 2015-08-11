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
                ->scalarNode('workspace_path')
                    ->defaultValue('%kernel.root_dir%/../workspace')
                ->end()
                ->scalarNode('relative_wWorkspace_url')
                    ->defaultValue('../workspace')
                ->end()
                ->scalarNode('web_path')
                    ->defaultValue('%kernel.root_dir%/../web')
                ->end()
                ->scalarNode('relative_web_url')
                    ->defaultValue('/')
                ->end()
                ->arrayNode('builds')
                    ->fixXmlConfig('build')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('development_base')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('production_base')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('development_manifest')
                                ->defaultValue('bootstrap.json')
                            ->end()
                            ->scalarNode('development_microloader')
                                ->defaultValue('bootstrap.js')
                            ->end()
                            ->scalarNode('production_manifest')
                                ->defaultValue('app.json')
                            ->end()
                            ->scalarNode('production_microloader')
                                ->defaultValue('bootstrap.js')
                            ->end()

                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}

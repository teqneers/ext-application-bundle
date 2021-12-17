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
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('tq_ext_js_application');
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('app_path')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('builds')
                    ->fixXmlConfig('build')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                             ->arrayNode('development')
                                ->isRequired()
                                ->children()
                                    ->scalarNode('build_path')
                                        ->isRequired()
                                        ->cannotBeEmpty()
                                    ->end()
                                    ->scalarNode('microloader')
                                        ->defaultValue('/bootstrap.js')
                                    ->end()
                                    ->scalarNode('manifest')
                                        ->defaultValue('/bootstrap.json')
                                    ->end()
                                    ->scalarNode('app_cache')
                                        ->defaultValue(null)
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('production')
                                ->isRequired()
                                ->children()
                                    ->scalarNode('build_path')
                                        ->isRequired()
                                        ->cannotBeEmpty()
                                    ->end()
                                    ->scalarNode('microloader')
                                        ->defaultValue('microloader.js')
                                    ->end()
                                    ->scalarNode('manifest')
                                        ->defaultValue('app.json')
                                    ->end()
                                    ->scalarNode('app_cache')
                                        ->defaultValue('cache.appcache')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}

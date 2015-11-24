<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 24.11.15
 * Time: 11:12
 */

namespace TQ\Bundle\ExtJSApplicationBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ManifestMutatorPass
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\DependencyInjection\Compiler
 */
class ManifestMutatorPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('tq_extjs.manifest_loader')) {
            return;
        }

        $definition = $container->getDefinition('tq_extjs.manifest_loader');
        foreach ($container->findTaggedServiceIds('tq_extjs.manifest_mutator') as $id => $manifestMutators) {
            foreach ($manifestMutators as $manifestMutator) {
                $priority = isset($manifestMutator['priority']) ? (int)$manifestMutator['priority'] : 0;
                $definition->addMethodCall('addManifestMutator', array(new Reference($id), $priority));
            }
        }
    }
}

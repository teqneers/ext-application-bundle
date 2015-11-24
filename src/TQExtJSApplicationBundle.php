<?php
/**
 * teqneers/ext-application-bundle
 *
 * @category   TQ
 * @package    TQ\Bundle\ExtApplicationBundle
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\Bundle\ExtJSApplicationBundle;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TQ\Bundle\ExtJSApplicationBundle\DependencyInjection\Compiler\ManifestMutatorPass;

/**
 * Class TQExtJSApplicationBundle
 *
 * @package TQ\Bundle\ExtApplicationBundle
 */
class TQExtJSApplicationBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ManifestMutatorPass());
    }
}

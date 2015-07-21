<?php
/**
 * teqneers/ext-application-bundle
 *
 * @category   TQ
 * @package    TQ\Bundle\ExtApplicationBundle
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\Bundle\ExtJSApplicationBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ExtJSExtension
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\Twig
 */
class ExtJSExtension extends \Twig_Extension
{
    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * @param UrlGeneratorInterface $generator
     */
    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'extjsManifestPath',
                [$this, 'getManifestPath']
            ),
            new \Twig_SimpleFunction(
                'extjsBootstrapPath',
                [$this, 'getBootstrapPath']
            ),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'tq_extjs_extension';
    }

    /**
     * @return string
     */
    public function getManifestPath()
    {
        return $this->generator->generate('tq_extjs_application_manifest');
    }

    /**
     * @return string
     */
    public function getBootstrapPath()
    {
        return $this->generator->generate('tq_extjs_application_bootstrap');
    }
}

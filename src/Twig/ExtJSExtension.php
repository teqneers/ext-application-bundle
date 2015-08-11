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
use TQ\ExtJS\Application\Application;

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
     * @var Application
     */
    private $application;

    /**
     * @param UrlGeneratorInterface $generator
     * @param Application           $application
     */
    public function __construct(UrlGeneratorInterface $generator, Application $application)
    {
        $this->generator   = $generator;
        $this->application = $application;
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
            new \Twig_SimpleFunction(
                'extjsAppCachePath',
                [$this, 'getAppCachePath']
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
     * @param string|null $build
     * @return string
     */
    public function getManifestPath($build = null)
    {
        return $this->generator->generate('tq_extjs_application_manifest', [
            'build' => $build ?: $this->application->getDefaultBuild(),
            'dev'   => $this->application->isDevelopment() ? '-dev' : ''
        ]);
    }

    /**
     * @param string|null $build
     * @return string
     */
    public function getBootstrapPath($build = null)
    {
        return $this->generator->generate('tq_extjs_application_bootstrap', [
            'build' => $build ?: $this->application->getDefaultBuild(),
            'dev'   => $this->application->isDevelopment() ? '-dev' : ''
        ]);
    }

    /**
     * @param string|null $build
     * @return string
     */
    public function getAppCachePath($build = null)
    {
        $build = $build ?: $this->application->getDefaultBuild();
        if (!$this->application->hasAppCache($build)) {
            return '';
        }
        return $this->generator->generate('tq_extjs_application_appcache', [
            'build' => $build,
            'dev'   => $this->application->isDevelopment() ? '-dev' : ''
        ]);
    }
}

<?php
/**
 * teqneers/ext-application-bundle
 *
 * @category   TQ
 * @package    TQ\Bundle\ExtApplicationBundle
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\Bundle\ExtJSApplicationBundle\Twig;

use TQ\Bundle\ExtJSApplicationBundle\Helper\TemplatingHelper;

/**
 * Class ExtJSExtension
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\Twig
 */
class ExtJSExtension extends \Twig_Extension
{
    /**
     * @var TemplatingHelper
     */
    private $templatingHelper;

    /**
     * @param TemplatingHelper $templatingHelper
     */
    public function __construct(TemplatingHelper $templatingHelper)
    {
        $this->templatingHelper = $templatingHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'extjsManifestPath',
                [$this->templatingHelper, 'getManifestPath']
            ),
            new \Twig_SimpleFunction(
                'extjsBootstrapPath',
                [$this->templatingHelper, 'getBootstrapPath']
            ),
            new \Twig_SimpleFunction(
                'extjsAppCachePath',
                [$this->templatingHelper, 'getAppCachePath']
            ),
            new \Twig_SimpleFunction(
                'extjsApplicationId',
                [$this->templatingHelper, 'getApplicationId']
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
}

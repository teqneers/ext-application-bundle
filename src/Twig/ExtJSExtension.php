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
use Twig\TwigFunction;

/**
 * Class ExtJSExtension
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\Twig
 */
class ExtJSExtension extends \Twig\Extension\AbstractExtension
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
            new TwigFunction(
                'extjsManifestPath',
                [$this->templatingHelper, 'getManifestPath']
            ),
            new TwigFunction(
                'extjsBootstrapPath',
                [$this->templatingHelper, 'getBootstrapPath']
            ),
            new TwigFunction(
                'extjsAppCachePath',
                [$this->templatingHelper, 'getAppCachePath']
            ),
            new TwigFunction(
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

<?php
/**
 * teqneers/ext-application-bundle
 *
 * @category   TQ
 * @package    TQ\Bundle\ExtApplicationBundle
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\Bundle\ExtJSApplicationBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use TQ\Bundle\ExtJSApplicationBundle\Helper\TemplatingHelper;

/**
 * Class ExtJSHelper
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\Templating\Helper
 */
class ExtJSHelper extends Helper
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
    public function getName()
    {
        return 'tq_extjs';
    }

    /**
     * @param string|null $build
     * @return string
     */
    public function getManifestPath($build = null)
    {
        return $this->templatingHelper->getManifestPath($build);
    }

    /**
     * @param string|null $build
     * @return string
     */
    public function getBootstrapPath($build = null)
    {
        return $this->templatingHelper->getBootstrapPath($build);
    }

    /**
     * @param string|null $build
     * @return string
     */
    public function getAppCachePath($build = null)
    {
        return $this->templatingHelper->getAppCachePath($build);
    }

    /**
     * @return string
     */
    public function getApplicationId()
    {
        return $this->templatingHelper->getApplicationId();
    }
}

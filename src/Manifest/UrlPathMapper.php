<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 02.09.15
 * Time: 11:59
 */

namespace TQ\Bundle\ExtJSApplicationBundle\Manifest;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use TQ\ExtJS\Application\Manifest\PathMapperInterface;

/**
 * Class UrlPathMapper
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\Manifest
 */
class UrlPathMapper implements PathMapperInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function mapPath($path, $build, $development)
    {
        if (substr($path, 0, 1) === '/') {
            return $path;
        }

        return $this->urlGenerator->generate('tq_extjs_application_resources', [
            'build' => $build,
            'dev'   => $development ? '-dev' : '',
            'path'  => $path
        ]);
    }
}

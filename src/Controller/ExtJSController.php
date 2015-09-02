<?php
/**
 * teqneers/ext-application-bundle
 *
 * @category   TQ
 * @package    TQ\Bundle\ExtApplicationBundle
 * @copyright  Copyright (C) 2015 by TEQneers GmbH & Co. KG
 */

namespace TQ\Bundle\ExtJSApplicationBundle\Controller;


use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TQ\ExtJS\Application\Application;
use TQ\ExtJS\Application\Exception\FileNotFoundException;

/**
 * Class ExtJSController
 *
 * @package TQ\Bundle\ExtJSApplicationBundle\Controller
 */
class ExtJSController
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param string $build
     * @return Response
     */
    public function bootstrapAction($build)
    {
        try {
            $bootstrapFile = $this->application->getMicroLoaderFile($build);
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('Not Found', $e);
        }

        return $this->createBinaryFileResponse($bootstrapFile, 'application/javascript');
    }

    /**
     * @param string  $build
     * @param Request $request
     * @return Response
     */
    public function manifestAction($build, Request $request)
    {
        try {
            $manifest = $this->application->getManifest($build);
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('Not Found', $e);
        }

        $response = new StreamedResponse(
            function () {
                echo '';
            }
        );
        $response->setETag($manifest->computeETag())
                 ->setLastModified(\DateTime::createFromFormat('U', $manifest->getMTime()))
                 ->setPublic();

        if ($response->isNotModified($request)) {
            return $response;
        }

        $response->setCallback(function () use ($manifest) {
            echo $manifest->getContent();
        });

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param string $build
     * @return Response
     */
    public function appCacheAction($build)
    {
        try {
            $appCacheFile = $this->application->getAppCacheFile($build);
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('Not Found', $e);
        }

        return $this->createBinaryFileResponse($appCacheFile, 'text/cache-manifest');
    }

    /**
     * @param string $build
     * @param string $path
     * @return Response
     */
    public function resourcesAction($build, $path)
    {
        try {
            $buildArtifact = $this->application->getBuildArtifact($path, $build);
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('Not Found', $e);
        }

        $file = new \Symfony\Component\HttpFoundation\File\File($buildArtifact->getPathname());

        if ($file->getExtension() == 'css') {
            $contentType = 'text/css';
        } elseif ($file->getExtension() == 'js') {
            $contentType = 'text/javascript';
        } elseif ($mimeType = $file->getMimeType()) {
            $contentType = $mimeType;
        } else {
            $contentType = 'text/plain';
        }

        return $this->createBinaryFileResponse($file, $contentType);
    }

    /**
     * @param \SplFileInfo $file
     * @param string       $contentType
     * @return BinaryFileResponse
     */
    private function createBinaryFileResponse(\SplFileInfo $file, $contentType)
    {
        $response = new BinaryFileResponse($file);
        $response->setPublic();
        $response->headers->set('Content-Type', $contentType);

        return $response;
    }
}

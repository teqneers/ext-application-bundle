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
     * @param string  $build
     * @param Request $request
     * @return Response
     */
    public function bootstrapAction($build, Request $request)
    {
        try {
            $bootstrapFile = $this->application->getMicroLoaderFile($build);
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('Not Found', $e);
        }

        return $this->createBinaryFileResponse($request, $bootstrapFile, 'application/javascript');
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
     * @param string  $build
     * @param Request $request
     * @return Response
     */
    public function appCacheAction($build, Request $request)
    {
        try {
            $appCacheFile = $this->application->getAppCacheFile($build);
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('Not Found', $e);
        }

        return $this->createBinaryFileResponse($request, $appCacheFile, 'text/cache-manifest');
    }

    /**
     * @param string  $build
     * @param string  $path
     * @param Request $request
     * @return Response
     */
    public function resourcesAction($build, $path, Request $request)
    {
        try {
            $buildArtifact = $this->application->getBuildArtifact(str_replace('~', '..', $path), $build);
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('Not Found', $e);
        }

        $file = new \Symfony\Component\HttpFoundation\File\File($buildArtifact->getPathname());

        switch ($file->getExtension()) {
            case 'css':
                $contentType = 'text/css';
                break;
            case 'js':
                $contentType = 'text/javascript';
                break;
            case 'svg':
                $contentType = 'image/svg+xml';
                break;
            case 'ttf':
                $contentType = 'application/x-font-ttf';
                break;
            case 'otf':
                $contentType = 'application/x-font-opentype';
                break;
            case 'eot':
                $contentType = 'application/vnd.ms-fontobject';
                break;
            case 'woff':
                $contentType = 'application/font-woff';
                break;
            case 'woff2':
                $contentType = 'application/font-woff2';
                break;
            case 'sfnt':
                $contentType = 'application/font-sfnt';
                break;
            default:
                if ($mimeType = $file->getMimeType()) {
                    $contentType = $mimeType;
                } else {
                    $contentType = 'text/plain';
                }
                break;
        }

        return $this->createBinaryFileResponse($request, $file, $contentType);
    }

    /**
     * @param Request      $request
     * @param \SplFileInfo $file
     * @param string       $contentType
     * @return BinaryFileResponse
     */
    private function createBinaryFileResponse(Request $request, \SplFileInfo $file, $contentType)
    {
        $response = new BinaryFileResponse($file, Response::HTTP_OK, array(
            'Content-Type' => $contentType
        ), true, null, true, true);

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}

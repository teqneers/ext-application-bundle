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
     * @return Response
     */
    public function bootstrapAction()
    {
        try {
            $bootstrapFile = $this->application->getMicroLoaderFile();
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('Not Found', $e);
        }

        return new BinaryFileResponse(
            $bootstrapFile,
            Response::HTTP_OK,
            array(
                'Content-Type'  => 'application/javascript',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Pragma'        => 'public',
                'Expires'       => 0,
            )
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function manifestAction(Request $request)
    {
        try {
            $manifest = $this->application->getManifest($request->getBasePath());
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('Not Found', $e);
        }

        return new StreamedResponse(
            function () use ($manifest) {
                echo $manifest;
            },
            Response::HTTP_OK,
            array(
                'Content-Type'  => 'application/json',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Pragma'        => 'public',
                'Expires'       => 0,
            )
        );
    }
}

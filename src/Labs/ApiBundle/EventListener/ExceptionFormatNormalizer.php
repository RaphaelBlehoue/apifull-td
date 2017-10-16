<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 16/10/2017
 * Time: 13:23
 */

namespace Labs\ApiBundle\EventListener;


use Symfony\Component\HttpFoundation\Response;

final class ExceptionFormatNormalizer
{
    const ExceptionFormat = [
        "Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException" => [
            'errorFormat' => [
                'message'    => 'Methode non autorisé',
                'statusCode' => Response::HTTP_METHOD_NOT_ALLOWED,
                'code'       => 1405,
                'type'       => 'MethodNotAllowedHttpException'
            ]
        ],
        "Symfony\Component\HttpKernel\Exception\NotFoundHttpException" => [
            'errorFormat' => [
                'message'    => 'Page ou ressource introuvable',
                'statusCode' => Response::HTTP_NOT_FOUND,
                'code'       => 1404
            ]
        ]
    ];
}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 17/10/2017
 * Time: 19:08
 */

namespace Labs\ApiBundle\Exception;


class FormatValidationErrors
{

    public function RessourceValidation( array $error = array() , $response, $code = 0)
    {
        $message = $this->getFormatMessage($response, $code);
        $arr = array_merge($error, $message);
        return $arr;
    }

    private function getFormatMessage($reponse, $code)
    {
        return $message = [
            'status'           => 'failure',
            'exception'        => 'RessourceValidationErrors',
            'message'          => 'Erreur de validation des données',
            'statusCode'       => $reponse,
            'code'             => $code
        ];
    }
}
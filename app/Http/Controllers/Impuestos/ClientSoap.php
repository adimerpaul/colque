<?php

namespace App\Http\Controllers\Impuestos;

use SoapClient;

class ClientSoap
{
    public static function getClient($wsdl, $token)
    {
        $opts = array(
            'http' => array(
                'header' => "apikey: TokenApi {$token}",
            )
        );
        $context = stream_context_create($opts);

        return new SoapClient($wsdl,  [
            'stream_context' => $context,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
            'trace' => 1,
            'use' => SOAP_LITERAL,
            'style' => SOAP_DOCUMENT,
        ]);
    }
}

<?php

namespace ForTheLocal\Laravel\OpenID;

use Exception;
use GuzzleHttp\Client as HttpClient;

/**
 * Class JWKResponse
 *
 * represents JWK(Json Web Key) response from issuer
 * https://tools.ietf.org/html/rfc7517
 *
 * @package ForTheLocal\Laravel\OpenID
 */
class JWKResponse
{
    private $jwksUri;
    private $data;

    function __construct(Discovery $discovery)
    {
        $this->jwksUri = $discovery->getJwksUri();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function fetchKeys(): string
    {
        $client = $this->getConfiguredHttpClient();
        $res = $client->get($this->jwksUri);
        if ($res->getStatusCode() != 200) {
            throw new Exception('fail to connect to endpoint.');
        }

        $this->data = $res->getBody();

        return $this->data;
    }


    public function getConfiguredHttpClient()
    {
        $client = new HttpClient();

        return $client;
    }


}

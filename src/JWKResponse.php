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

    /**
     * JWKResponse constructor.
     * @param Discovery $discovery
     */
    function __construct(Discovery $discovery)
    {
        $this->jwksUri = $discovery->getJwksUri();
    }

    /**
     * @param null $data
     * @throws Exception
     */
    function init($data = null)
    {
        if (empty($data)) {
            $this->data = json_decode($this->fetchKeys(), true);
        } else {
            $this->data = json_decode($data, true);
        }
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

    public function getKey(string $kid): ?JWK
    {
        foreach($this->data['keys'] as $key) {
            if ($key['kid'] == $kid) {
                return new JWK($key);
            }
        }

        return null;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function getConfiguredHttpClient()
    {
        $client = new HttpClient();

        return $client;
    }


}

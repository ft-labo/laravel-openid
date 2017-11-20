<?php

namespace ForTheLocal\Laravel\OpenID;

use Exception;
use GuzzleHttp\Client as HttpClient;


/**
 * Class Discovery
 *
 * OpenID Connect Discovery 1.0
 * http://openid.net/specs/openid-connect-discovery-1_0.html
 *
 * @package ForTheLocal\Laravel\OpenID
 */
class Discovery
{
    const DURATION_DEFAULT = 60 * 24; // 24 hours
    const WELL_KNOWN_PATH = '.well-known/openid-configuration';
    protected $primaryKey = 'issuer';
    protected $fillable = ['data'];

    protected $jwt;
    protected $data;

    // TODO enable cache the data in db by configuration with env.
    protected $cacheEnabled = false;

    /**
     * Discovery constructor.
     * @param JWT $jwt
     */
    function __construct(JWT $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * @param null $data
     * @throws Exception
     */
    public function init($data = null)
    {
        if (empty($data)) {
            $this->data = json_decode($this->fetchConfiguration(), true);
        } else {
            $this->data = json_decode($data, true);
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    public function fetchConfiguration(): string
    {
        $client = $this->getConfiguredHttpClient();
        $res = $client->get(self::WELL_KNOWN_PATH.'?p='.$this->jwt->getClaim('tfp'));
        if ($res->getStatusCode() != 200) {
            throw new Exception('fail to connect to endpoint.');
        }

        return $res->getBody();
    }

    public function getJwksUri(): string
    {
        return $this->data['jwks_uri'];
    }

    public function refreshCache()
    {
        // TODO
    }

    public function getConfiguredHttpClient(): HttpClient
    {
        $client = new HttpClient(["base_uri" => $this->jwt->getIssuer()]);

        return $client;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

}

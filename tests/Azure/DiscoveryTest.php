<?php

namespace ForTheLocal\Laravel\OpenID\Azure;

use ForTheLocal\Laravel\OpenID\JWT;
use ForTheLocal\Test\TestCase as TestCase;
use Lcobucci\JWT\Builder;
use ForTheLocal\Laravel\OpenID\Azure\Discovery as AzureDiscovery;

class DiscoveryTest extends TestCase
{

    public function testGetJwksUriWithFtpExists()
    {
        $tfp = 'signup';
        $jwt = new JWT($this->getDummyToken($tfp));
        $discovery = new AzureDiscovery($jwt);

        $client = $discovery->getConfiguredHttpClient();

        $expected = "/tfp/tenantid/{$tfp}/v2.0/";
        $this->assertEquals($expected, $client->getConfig('base_uri')->getPath());
    }

    public function testGetJwksUriWithFtpNotExists()
    {
        $jwt = new JWT($this->getDummyToken());
        $discovery = new AzureDiscovery($jwt);

        $expected = "/tenantid/v2.0/";

        $client = $discovery->getConfiguredHttpClient();
        $this->assertEquals($expected, $client->getConfig('base_uri')->getPath());
    }

    private function getDummyToken(string $tfp = null)
    {
        $builder = (new Builder())->setIssuer('https://login.microsoftonline.com/tenantid/v2.0/')
        ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
        ->setExpiration(time() + 100); // Configures the expiration time of the token (exp claim)

        if (!empty($tfp)) {
            $builder->set('tfp', $tfp);
        }

        $token = $builder->getToken(); // Retrieves the generated token


        return $token;
    }

}

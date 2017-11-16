<?php

namespace ForTheLocal\Laravel\OpenID;

use ForTheLocal\Test\TestCase as TestCase;
use GuzzleHttp\Client as HttpClient;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class DiscoveryTest extends TestCase
{

    public function testInitWithData()
    {
        $configJson = file_get_contents(__DIR__ . '/asset/json/configuration.json');
        $discovery = new Discovery($this->getDummyJWT());
        $discovery->init($configJson);
        $this->assertEquals(json_decode($configJson, true), $discovery->getData());
        $this->assertEquals("https://example.com/discovery/v2.0/keys", $discovery->getJwksUri());
    }

    public function testInitWithNoData()
    {
        $discovery = \Mockery::spy(Discovery::class, [$this->getDummyJWT()])->makePartial();
        $discovery->shouldReceive("fetchConfiguration")->once();
        $discovery->init();
    }


    public function testFetchConfiguration()
    {
        $configJson = file_get_contents(__DIR__ . '/asset/json/configuration.json');

        $jwt = $this->getDummyJWT();
        $discovery = \Mockery::mock(Discovery::class, [$jwt])->makePartial();

        $mockResponse = \Mockery::mock();
        $mockResponse->shouldReceive('getStatusCode')->andReturn(200);
        $mockResponse->shouldReceive('getBody')->andReturn($this->minifyJson($configJson));

        $mockClient = \Mockery::mock(HttpClient::class);
        $mockClient->shouldReceive('get')->andReturn($mockResponse);

        $discovery->shouldReceive('getConfiguredHttpClient')->andReturn($mockClient);

        $this->assertEquals($this->minifyJson($configJson), $discovery->fetchConfiguration());
    }

    private function getDummyJWT()
    {

        $privateKey = file_get_contents(__DIR__ . '/asset/cert/private1');

        $signer = new Sha256();
        $jwt = (new Builder())->setIssuer('http://example.com')// Configures the issuer (iss claim)
        ->setAudience('http://example.org')// Configures the audience (aud claim)
        ->setId('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
        ->setIssuedAt(time())// Configures the time that the token was issue (iat claim)
        ->setExpiration(time() + 100)// Configures the expiration time of the token (exp claim)
        ->set('uid', 1)// Configures a new claim, called "uid"
        ->sign($signer, new Key($privateKey))// creates a signature using your private key
        ->getToken(); // Retrieves the generated token

        return new JWT($jwt);
    }


}

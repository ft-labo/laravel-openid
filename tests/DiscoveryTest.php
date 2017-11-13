<?php

namespace ForTheLocal\Laravel\OpenID;

use Exception;
use ForTheLocal\Test\TestCase as TestCase;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class DiscoveryTest extends TestCase
{

    public function testGetJwksUri()
    {

        $configJson = file_get_contents(__DIR__ . '/asset/json/configuration.json');

        $jwt = \Mockery::mock(JWT::class);
        $discovery = new Discovery($jwt, ['data' => $configJson]);


        $this->assertEquals("https://example.com/discovery/v2.0/keys", $discovery->getJwksUri());

    }

    public function testFetchConfiguration()
    {
        $configJson = file_get_contents(__DIR__ . '/asset/json/configuration.json');

        $jwt = \Mockery::mock(JWT::class);
        $discovery = \Mockery::mock(Discovery::class, [$jwt, ['data' => $configJson]])->makePartial();

        $mockResponse = \Mockery::mock();
        $mockResponse->shouldReceive('getStatusCode')->andReturn(200);
        $mockResponse->shouldReceive('getBody')->andReturn($this->minifyJson($configJson));

        $mockClient = \Mockery::mock();
        $mockClient->shouldReceive('get')->andReturn($mockResponse);

        $discovery->shouldReceive('getConfiguredHttpClient')->andReturn($mockClient);

        $this->assertEquals($this->minifyJson($configJson), $discovery->fetchConfiguration());
    }


}

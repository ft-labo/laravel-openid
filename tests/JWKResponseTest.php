<?php

namespace ForTheLocal\Laravel\OpenID;

use ForTheLocal\Test\TestCase;

class JWKResponseTest extends TestCase
{

    public function testFetchKeys()
    {
        $discovery = \Mockery::mock(Discovery::class);
        $discovery->shouldReceive("getJwksUri")->andReturn('https://example.com');

        $jwkResponse = \Mockery::mock(JWKResponse::class, [$discovery])->makePartial();


        $json = file_get_contents(__DIR__ . '/asset/json/jwk_response.json');
        $mockResponse = \Mockery::mock();
        $mockResponse->shouldReceive('getStatusCode')->andReturn(200);
        $mockResponse->shouldReceive('getBody')->andReturn($this->minifyJson($json));

        $mockClient = \Mockery::mock();
        $mockClient->shouldReceive('get')->andReturn($mockResponse);

        $jwkResponse->shouldReceive('getConfiguredHttpClient')->andReturn($mockClient);

        $this->assertEquals($this->minifyJson($json), $jwkResponse->fetchKeys());
    }


}

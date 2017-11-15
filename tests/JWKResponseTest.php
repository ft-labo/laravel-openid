<?php

namespace ForTheLocal\Laravel\OpenID;

use ForTheLocal\Test\TestCase;

class JWKResponseTest extends TestCase
{

    public function testInitWithNoData()
    {
        $discovery = $this->getMockDiscovery();

        $jwkResponse = \Mockery::spy(JWKResponse::class, [$discovery])->makePartial();
        $jwkResponse->shouldReceive("fetchKeys")->once();
        $jwkResponse->init();
    }

    public function testInitWithData()
    {
        $discovery = $this->getMockDiscovery();
        $jwkResponse = \Mockery::mock(JWKResponse::class, [$discovery])->makePartial();

        $mockClient = \Mockery::spy();
        $mockClient->shouldNotReceive('getStatusCode');
        $mockClient->shouldNotReceive('get');

        $json = file_get_contents(__DIR__ . '/asset/json/jwk_response.json');
        $jwkResponse->init($json);
        $this->assertEquals(json_decode($json, true), $jwkResponse->getData());
    }

    public function testFetchKeys()
    {
        $discovery = $this->getMockDiscovery();

        $jwkResponse = \Mockery::mock(JWKResponse::class, [$discovery])->makePartial();

        $json = file_get_contents(__DIR__ . '/asset/json/jwk_response.json');
        $mockResponse = \Mockery::mock();
        $mockResponse->shouldReceive('getStatusCode')->andReturn(200);
        $mockResponse->shouldReceive('getBody')->andReturn($this->minifyJson($json));

        $mockClient = \Mockery::spy();
        $mockClient->shouldReceive('get')->andReturn($mockResponse)->once();

        $jwkResponse->shouldReceive('getConfiguredHttpClient')->andReturn($mockClient);
        $this->assertEquals($this->minifyJson($json), $jwkResponse->fetchKeys());
    }

    public function testGetKey()
    {
        $discovery = $this->getMockDiscovery();
        $jwkResponse = \Mockery::mock(JWKResponse::class, [$discovery])->makePartial();
        $json = file_get_contents(__DIR__ . '/asset/json/jwk_response.json');
        $jwkResponse->init($json);

        $this->assertNotNull($jwkResponse->getKey("915a8ff6b6f6b226741fd197"));
        $this->assertNull($jwkResponse->getKey("dummy"));
    }

    private function getMockDiscovery()
    {
        $discovery = \Mockery::mock(Discovery::class);
        $discovery->shouldReceive("getJwksUri")->andReturn('https://example.com');
        return $discovery;
    }
}

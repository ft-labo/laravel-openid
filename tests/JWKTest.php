<?php

namespace ForTheLocal\Laravel\OpenID;

use ForTheLocal\Test\TestCase as TestCase;

class JWKTest extends TestCase
{


    public function testGeneratePem()
    {
        $jwk = $this->getKey();
        $this->assertContains('-----BEGIN PUBLIC KEY-----', $jwk->generatePem());
    }

    public function testGetPublicKey()
    {
        $jwk = $this->getKey();

        $this->assertInternalType('resource', $jwk->getPublicKey());
    }

    private function getKey()
    {
        $jwks = file_get_contents(__DIR__ . '/asset/json/jwk_response.json');

        $discovery = \Mockery::mock(Discovery::class);
        $jwkResponse = new JWKResponse($discovery);
        $jwkResponse->init($jwks);

        return $jwkResponse->getKey('915a8ff6b6f6b226741fd197');
    }


}

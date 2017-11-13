<?php

namespace ForTheLocal\Laravel\OpenID;

use ForTheLocal\Test\TestCase as TestCase;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class JWTTest extends TestCase
{

    public function testGetIssuer()
    {
        $privateKey = file_get_contents(__DIR__ . '/asset/cert/private1');
        $token = $this->createDummyIdToken($privateKey, 100);
        $jwt = new JWT($token);
        $this->assertEquals('http://example.com', $jwt->getIssuer());
    }
    public function testVerify()
    {
        $privateKey = file_get_contents(__DIR__ . '/asset/cert/private1');
        $token = $this->createDummyIdToken($privateKey, 100);
        $jwt = new JWT($token);
        $publicKey = file_get_contents(__DIR__ . '/asset/cert/public1');

        $this->assertTrue($jwt->verify(new Sha256(), $publicKey));
    }

    public function testFailVerifyIfCertIsNotMuch()
    {
        $privateKey = file_get_contents(__DIR__ . '/asset/cert/private1');
        $token = $this->createDummyIdToken($privateKey, 100);
        $jwt = new JWT($token);
        $publicKey = file_get_contents(__DIR__ . '/asset/cert/public2');

        $this->assertFalse($jwt->verify(new Sha256(), $publicKey));
    }


    private function createDummyIdToken(string $privateKey, int $expiresAt)
    {

        $signer = new Sha256();
        $token = (new Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
                                ->setAudience('http://example.org') // Configures the audience (aud claim)
                                ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
                                ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                                ->setExpiration(time() + $expiresAt) // Configures the expiration time of the token (exp claim)
                                ->set('uid', 1) // Configures a new claim, called "uid"
                                ->sign($signer,  new Key($privateKey)) // creates a signature using your private key
                                ->getToken(); // Retrieves the generated token


        return $token;
    }

}

<?php

namespace ForTheLocal\Laravel\OpenID;

use ForTheLocal\Test\TestCase as TestCase;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class JWTTest extends TestCase
{

    public function testIsExpired()
    {
        $privateKey = file_get_contents(__DIR__ . '/asset/cert/private1');
        $token = $this->createDummyIdToken($privateKey, 100);
        $jwt = new JWT($token);
        $this->assertFalse($jwt->isExpired());

        $tokenExpired = $this->createDummyIdToken($privateKey, -1);
        $jwtExpired = new JWT($tokenExpired);
        $this->assertTrue($jwtExpired->isExpired());
    }

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
        $token = $this->createDummyIdToken($privateKey, 100, new Signer\RSA\Sha256());
        $jwt = new JWT($token);
        $publicKey = file_get_contents(__DIR__ . '/asset/cert/public1');

        $jwk = \Mockery::mock(JWK::class);
        $jwk->shouldReceive('generatePem')->andReturn($publicKey);

        $this->assertTrue($jwt->verify($jwk));
    }

    public function testVerifyOtherSigner()
    {
        $privateKey = file_get_contents(__DIR__ . '/asset/cert/private1');
        $token = $this->createDummyIdToken($privateKey, 100, new Signer\RSA\Sha512());
        $jwt = new JWT($token);
        $publicKey = file_get_contents(__DIR__ . '/asset/cert/public1');

        $jwk = \Mockery::mock(JWK::class);
        $jwk->shouldReceive('generatePem')->andReturn($publicKey);

        $this->assertTrue($jwt->verify($jwk));
    }

    public function testFailVerifyIfCertIsNotMuch()
    {
        $privateKey = file_get_contents(__DIR__ . '/asset/cert/private1');
        $token = $this->createDummyIdToken($privateKey, 100, new Signer\RSA\Sha512());
        $jwt = new JWT($token);
        $publicKey = file_get_contents(__DIR__ . '/asset/cert/public2');

        $jwk = \Mockery::mock(JWK::class);
        $jwk->shouldReceive('generatePem')->andReturn($publicKey);

        $this->assertFalse($jwt->verify($jwk));
    }

    protected function createDummyIdToken(string $privateKey, int $expiresAt, Signer $signer = null)
    {

        $signer = $signer ?? new Sha256();
        $builder = (new Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
                                ->setAudience('http://example.org') // Configures the audience (aud claim)
                                ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
                                ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                                ->setExpiration(time() + $expiresAt) // Configures the expiration time of the token (exp claim)
                                ->set('uid', 1); // Configures a new claim, called "uid"

        $builder->setHeader('kid', '915a8ff6b6f6b226741fd197');
        $builder->sign($signer,  new Key($privateKey)); // creates a signature using your private key

        $token = $builder->getToken(); // Retrieves the generated token


        return $token;
    }

}

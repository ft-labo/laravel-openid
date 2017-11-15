<?php

namespace ForTheLocal\Laravel\OpenID;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key;


/**
 * Class JWT
 *
 * represents OpenID JWT
 *
 * @package ForTheLocal\Laravel\OpenID;
 */
class JWT
{

    protected $token;

    function __construct(string $str)
    {
        $this->token = (new Parser())->parse($str);
    }

    public function getHeaders()
    {
        return $this->token->getHeaders();
    }

    public function getClaims()
    {
        return $this->token->getClaims();
    }

    public function getKid(): string
    {
        return $this->token->getHeader('kid');
    }

    public function getSubject(): string
    {
        return $this->token->getClaim('sub');
    }

    public function getIssuer(): string
    {
        return $this->token->getClaim('iss');
    }

    public function getExpireAt()
    {
        return $this->token->getClaim('exp');
    }

    public function isExpired(): bool
    {
        return $this->getExpireAt() < time();
    }

    public function getAlgorithm()
    {
        return $this->token->getHeader('alg');
    }

    /**
     * @param JWK $jwk
     * @return bool
     * @throws \Exception
     */
    public function verify(JWK $jwk): bool
    {


        // https://www.rfc-editor.org/rfc/rfc7518.txt
        switch ($this->getAlgorithm()) {
            case "RS256":
                $signer = new Signer\Rsa\Sha256();
                break;
            case "HS256":
                $signer = new Signer\Hmac\Sha256();
                break;
            case "ES256":
                $signer = new Signer\Ecdsa\Sha256();
                break;
            case "RS384":
                $signer = new Signer\Rsa\Sha384();
                break;
            case "HS384":
                $signer = new Signer\Hmac\Sha384();
                break;
            case "ES384":
                $signer = new Signer\Ecdsa\Sha384();
                break;
            case "RS512":
                $signer = new Signer\Rsa\Sha512();
                break;
            case "HS512":
                $signer = new Signer\Hmac\Sha512();
                break;
            case "ES512":
                $signer = new Signer\Ecdsa\Sha512();
                break;
            default:
                throw new \Exception("UNSUPPORTED ALGORITHM: " . $this->getAlgorithm());
        }

        return $this->token->verify($signer, new Key($jwk->generatePem()));
    }

}

<?php

namespace ForTheLocal\Laravel\OpenID;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer;


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

    /**
     * @param string $publicKey
     * @return bool
     * @throws \Exception
     */
    public function verify(string $publicKey): bool
    {
        // https://www.rfc-editor.org/rfc/rfc7518.txt
        switch ($this->token->getHeader('alg')) {
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
                $signer = new Signer\Rsa\Sha256();
                break;
            case "HS384":
                $signer = new Signer\Hmac\Sha256();
                break;
            case "ES384":
                $signer = new Signer\Ecdsa\Sha256();
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
                throw new \Exception("UNSUPPORTED ALGORITHM: " . $this->token->getHeader('alg'));

        }
        return $this->token->verify($signer, $publicKey);
    }

    public function getHeaders()
    {
        return $this->token->getHeaders();
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

    public function getAlg()
    {

    }


}

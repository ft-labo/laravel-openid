<?php

namespace ForTheLocal\Laravel\OpenID;

use phpseclib\Math\BigInteger as Math_BigInteger;
use phpseclib\Crypt\RSA as Crypt_RSA;

/**
 * Class JWK
 *
 * represents JWK(Json Web Key)
 * http://openid-foundation-japan.github.io/rfc7638.ja.html
 *
 * @package ForTheLocal\Laravel\OpenID
 */
class JWK
{

    private $keyId;
    private $keyType;
    private $publicKeyUse;
    private $exponent;
    private $modulus;


    function __construct(array $key)
    {
        $this->keyId = $key['kid'];
        $this->keyType = $key['kty'];
        $this->publicKeyUse = $key['use'];
        $this->exponent = $key['e'];
        $this->modulus = $key['n'];
    }

    public function getPublicKey()
    {
        return openssl_get_publickey($this->generatePem());
    }

    public function generatePem()
    {

        $binModulus = $this->urlsafeBase64Decode($this->modulus);
        $binExponent = $this->urlsafeBase64Decode($this->exponent);

        $rsa = new Crypt_RSA();
        $rsa->loadKey(
            [
                'e' => $this->convertBinaryToBigInteger($binExponent),
                'n' => $this->convertBinaryToBigInteger($binModulus)
            ]
        );
        return $rsa->getPublicKey();
    }


    private function urlsafeBase64Decode($input): string
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }


    /**
     * convert to BigInteger from base64 encoded binary data.
     *
     * @param  string         base64 encoded binary data
     * @return Math_BigInteger /phpseclib/Math/BigInteger.php
     */
    private function convertBinaryToBigInteger(string $bin): Math_BigInteger
    {
        return new Math_BigInteger($bin, 256);
    }

}

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

    public function verify(Signer $signer, string $publicKey): bool
    {
        return $this->token->verify($signer, $publicKey);
    }


    public function getSubject(): string
    {
        return $this->token->getClaim('sub');
    }

    public function getIssuer(): string
    {
        return $this->token->getClaim('iss');
    }

    public function getAlg()
    {

    }


}

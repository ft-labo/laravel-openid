<?php

namespace ForTheLocal\Laravel\OpenID\Azure;

use GuzzleHttp\Client as HttpClient;

/**
 * Class JWT
 *
 * represents Azure OpenID JWT
 *
 * @package ForTheLocal\Laravel\OpenID\Azure;
 */
class Discovery extends \ForTheLocal\Laravel\OpenID\Discovery
{

    /**
     * see https://docs.microsoft.com/en-us/azure/active-directory-b2c/active-directory-b2c-token-session-sso
     * @return HttpClient
     */
    public function getConfiguredHttpClient(): HttpClient
     {

         if ($this->jwt->hasClaim('tfp')) {
             // https://login.microsoftonline.com/{B2C tenant GUID}/v2.0/
             // have to convert to
             // https://login.microsoftonline.com/tfp/{B2C tenant GUID}/{Policy ID}/v2.0/

             $tfp = $this->jwt->getClaim('tfp');

             $exploded = explode('/', $this->jwt->getIssuer());
             // 0 => https:
             // 1 => ""
             // 2 => login.microsoftonline.com
             // 3 => tenant
             // 4 => v2.0
             // 5 => ""

             array_splice($exploded, 3, 0, 'tfp');
             array_splice($exploded, 5, 0, $tfp);

             $baseUrl =  implode('/', $exploded);
         } else {
             $baseUrl = $this->jwt->getIssuer();
         }

         $client = new HttpClient(["base_uri" => $baseUrl]);

         return $client;
     }

}

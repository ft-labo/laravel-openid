<?php

namespace ForTheLocal\Laravel\OpenID;

use ForTheLocal\Test\TestCase as TestCase;

class JWKTest extends TestCase
{

    const CERTIFICATE_KEYS = "{\"keys\":[{\"kty\":\"RSA\",\"use\":\"sig\",\"kid\":\"2KVcuzqAidOLqWSaol7wgFRGCYo\",\"x5t\":\"2KVcuzqAidOLqWSaol7wgFRGCYo\",\"n\":\"40MK4ih03cjonv5Zz2PmxjkyAuQZlm5TEsCkcSYiGBYhVJLIyAz567Q2uvkW4jKUmsqD9Ic4l4vAW5hk4Qx9FRVwpF7BRMgEqYguqWDn53nrO1hkBO6GbrQHlunVFSVRAxnQZN6nP3GlL2E7gy_kZEHFHnGgEoI4XvjF9W4c2ST_CdtX9iCDC3zYWsabwKmJeNuYXPLrVWanopsUNp0kOKPaaYgJLDMAkShW-SUvNwv_hV_Te_eXxoGQj9I98OObqTnl2p4Ob6cQg39tpZuzszZa02Qlc14_Lx1HQaR2WFuARIQgl1JUtZ4EW3x5XQlCpRdw8KOHCkkTz2OseHDIIQ\",\"e\":\"AQAB\",\"x5c\":[\"MIIDBTCCAe2gAwIBAgIQQm0sN9lDrblM/7U/vYMVmTANBgkqhkiG9w0BAQsFADAtMSswKQYDVQQDEyJhY2NvdW50cy5hY2Nlc3Njb250cm9sLndpbmRvd3MubmV0MB4XDTE3MDkwNjAwMDAwMFoXDTE5MDkwNzAwMDAwMFowLTErMCkGA1UEAxMiYWNjb3VudHMuYWNjZXNzY29udHJvbC53aW5kb3dzLm5ldDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAONDCuIodN3I6J7+Wc9j5sY5MgLkGZZuUxLApHEmIhgWIVSSyMgM+eu0Nrr5FuIylJrKg/SHOJeLwFuYZOEMfRUVcKRewUTIBKmILqlg5+d56ztYZATuhm60B5bp1RUlUQMZ0GTepz9xpS9hO4Mv5GRBxR5xoBKCOF74xfVuHNkk/wnbV/Yggwt82FrGm8CpiXjbmFzy61Vmp6KbFDadJDij2mmICSwzAJEoVvklLzcL/4Vf03v3l8aBkI/SPfDjm6k55dqeDm+nEIN/baWbs7M2WtNkJXNePy8dR0GkdlhbgESEIJdSVLWeBFt8eV0JQqUXcPCjhwpJE89jrHhwyCECAwEAAaMhMB8wHQYDVR0OBBYEFNISA3dtAzEd0muqNDbWm3kvNlJDMA0GCSqGSIb3DQEBCwUAA4IBAQClLLoAvg3dYqWO63Z6O5L7yataGcilmL3YUqCFoRKsuwej2T833qyc1iLG0iWCGeWAUonKXuGwfCSSSj2E3ksLtgV6xmuMl+NuVPpRpQo+38n+OxUoWKu963dMxnORFENEqKW0pMioipMk/HBaW3aJWyH1oT2rZ3KhFm67SFjKscF8ShAE82tQQIFwEFAXjMItW2oZVGDz3vDOaJN5xC8rfA6xkXTdcCuzy74SalKkLhpBO8S3XIOBVRZw+l0Koog8YNqhsvGsGS+hGXXNlCZTg0I1tR3g2DcSuHRcuTZKh7Z7XPPsDgleNirtvYFEvdvD4K2I7gb2H1xQn87oYAIX\"],\"issuer\":\"https://login.microsoftonline.com/4721e7d5-eebe-4150-add6-744cade8c457/v2.0\"},{\"kty\":\"RSA\",\"use\":\"sig\",\"kid\":\"x478xyOplsM1H7NXk7Sx17x1upc\",\"x5t\":\"x478xyOplsM1H7NXk7Sx17x1upc\",\"n\":\"w-SDUgtC2nAU8-qIEFXfBd3z0qgNbk0D8OIFA9lgg17_-DqB2s5ydPomNLmaee1dRGy-ULxrxA80J5f3O7GLQy8SheO7JE4nCcCFnIeaRZCHznSlSIVMgIK4MlcGsyIx0th95on0LRICnDY6EOIIQPvseHejG4_j2ihLkzL3r9vc8zvooL8ZO_hUznJ085em9SkvMyhq9B04ZQKNRhTbP23AJheYIu3A6I1YVb3Kqh_q1IDERlF7OrQeL2pToMXQwinRNPoUq9QLy6LilxhfuFnpT3I5clUHT7odd2kZDjazKm-grWHyZxFEsDkB3-jHpggnf2PS0mbnL7gffs28dQ\",\"e\":\"AQAB\",\"x5c\":[\"MIIDBTCCAe2gAwIBAgIQG3bMDDyO6q1GrI5sdZXCrTANBgkqhkiG9w0BAQsFADAtMSswKQYDVQQDEyJhY2NvdW50cy5hY2Nlc3Njb250cm9sLndpbmRvd3MubmV0MB4XDTE3MTAxODAwMDAwMFoXDTE5MTAxOTAwMDAwMFowLTErMCkGA1UEAxMiYWNjb3VudHMuYWNjZXNzY29udHJvbC53aW5kb3dzLm5ldDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAMPkg1ILQtpwFPPqiBBV3wXd89KoDW5NA/DiBQPZYINe//g6gdrOcnT6JjS5mnntXURsvlC8a8QPNCeX9zuxi0MvEoXjuyROJwnAhZyHmkWQh850pUiFTICCuDJXBrMiMdLYfeaJ9C0SApw2OhDiCED77Hh3oxuP49ooS5My96/b3PM76KC/GTv4VM5ydPOXpvUpLzMoavQdOGUCjUYU2z9twCYXmCLtwOiNWFW9yqof6tSAxEZRezq0Hi9qU6DF0MIp0TT6FKvUC8ui4pcYX7hZ6U9yOXJVB0+6HXdpGQ42sypvoK1h8mcRRLA5Ad/ox6YIJ39j0tJm5y+4H37NvHUCAwEAAaMhMB8wHQYDVR0OBBYEFCO/QGygHvo1YiKeQVulJFVxO9dnMA0GCSqGSIb3DQEBCwUAA4IBAQBZTJK52b+QnBbLicaT5uxC3JnRwps6RovQzPZRBLpxATq4kj5jNMhegb5fx4Rc1dpepXWJHAGzD0Nwsab/vYSx7iqyU02IAUkwt3k7XyYK17R6gTgUAxEFBfRKM3PSFiH0b3tGA+baLT3BdY5U6ZqjxhFA0Rh7tzPZM1TO2WtENk3hKmG5r5GKECnwa5NiE5jxN+d6i8dqM+vMqDvIrfqTA3ooQWXpvs0I9YUWl/LjBNFqyY3rMzxLX3STobLFf8ayHIvVmtiFSM3glCO+8UtGKLwNnPFIfYx3VstJjOO8rjP0Z/oaZwhD0A7MrNp4ztwmXAIzYkGTVyDsNuQJgi1e\"],\"issuer\":\"https://login.microsoftonline.com/4721e7d5-eebe-4150-add6-744cade8c457/v2.0\"},{\"kty\":\"RSA\",\"use\":\"sig\",\"kid\":\"2S4SCVGs8Sg9LS6AqLIq6DpW-g8\",\"x5t\":\"2S4SCVGs8Sg9LS6AqLIq6DpW-g8\",\"n\":\"oZ-QQrNuB4ei9ATYrT61ebPtvwwYWnsrTpp4ISSp6niZYb92XM0oUTNgqd_C1vGN8J-y9wCbaJWkpBf46CjdZehrqczPhzhHau8WcRXocSB1u_tuZhv1ooAZ4bAcy79UkeLiG60HkuTNJJC8CfaTp1R97szBhuk0Vz5yt4r5SpfewIlBCnZUYwkDS172H9WapQu-3P2Qjh0l-JLyCkdrhvizZUk0atq5_AIDKRU-A0pRGc-EZhUL0LqUMz6c6M2s_4GnQaScv44A5iZUDD15B6e8Apb2yARohkWmOnmRcTVfes8EkfxjzZEzm3cNkvP0ogILyISHKlkzy2OmlU6iXw\",\"e\":\"AQAB\",\"x5c\":[\"MIIDKDCCAhCgAwIBAgIQBHJvVNxP1oZO4HYKh+rypDANBgkqhkiG9w0BAQsFADAjMSEwHwYDVQQDExhsb2dpbi5taWNyb3NvZnRvbmxpbmUudXMwHhcNMTYxMTE2MDgwMDAwWhcNMTgxMTE2MDgwMDAwWjAjMSEwHwYDVQQDExhsb2dpbi5taWNyb3NvZnRvbmxpbmUudXMwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQChn5BCs24Hh6L0BNitPrV5s+2/DBhaeytOmnghJKnqeJlhv3ZczShRM2Cp38LW8Y3wn7L3AJtolaSkF/joKN1l6GupzM+HOEdq7xZxFehxIHW7+25mG/WigBnhsBzLv1SR4uIbrQeS5M0kkLwJ9pOnVH3uzMGG6TRXPnK3ivlKl97AiUEKdlRjCQNLXvYf1ZqlC77c/ZCOHSX4kvIKR2uG+LNlSTRq2rn8AgMpFT4DSlEZz4RmFQvQupQzPpzozaz/gadBpJy/jgDmJlQMPXkHp7wClvbIBGiGRaY6eZFxNV96zwSR/GPNkTObdw2S8/SiAgvIhIcqWTPLY6aVTqJfAgMBAAGjWDBWMFQGA1UdAQRNMEuAEDUj0BrjP0RTbmoRPTRMY3WhJTAjMSEwHwYDVQQDExhsb2dpbi5taWNyb3NvZnRvbmxpbmUudXOCEARyb1TcT9aGTuB2Cofq8qQwDQYJKoZIhvcNAQELBQADggEBAGnLhDHVz2gLDiu9L34V3ro/6xZDiSWhGyHcGqky7UlzQH3pT5so8iF5P0WzYqVtogPsyC2LPJYSTt2vmQugD4xlu/wbvMFLcV0hmNoTKCF1QTVtEQiAiy0Aq+eoF7Al5fV1S3Sune0uQHimuUFHCmUuF190MLcHcdWnPAmzIc8fv7quRUUsExXmxSX2ktUYQXzqFyIOSnDCuWFm6tpfK5JXS8fW5bpqTlrysXXz/OW/8NFGq/alfjrya4ojrOYLpunGriEtNPwK7hxj1AlCYEWaRHRXaUIW1ByoSff/6Y6+ZhXPUe0cDlNRt/qIz5aflwO7+W8baTS4O8m/icu7ItE=\"],\"issuer\":\"https://login.microsoftonline.com/4721e7d5-eebe-4150-add6-744cade8c457/v2.0\"}]}";

    public function testGeneratePem()
    {
        $jwk = new JWK($this->getKey());
        $this->assertContains('-----BEGIN PUBLIC KEY-----', $jwk->generatePem());
    }

    public function testGetPublicKey()
    {
        $jwk = new JWK($this->getKey());

        $this->assertInternalType('resource', $jwk->getPublicKey());
    }

    private function getKey()
    {
        $jsonArray = json_decode(self::CERTIFICATE_KEYS, true);

        return $jsonArray['keys'][0];
    }


}

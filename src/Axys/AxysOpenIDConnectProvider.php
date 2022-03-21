<?php

namespace Axys;

use Lcobucci\JWT\Signer\Rsa\Sha256;
use League\OAuth2\Client\OptionProvider\HttpBasicAuthOptionProvider;
use OpenIDConnectClient\OpenIDConnectProvider;

class AxysOpenIDConnectProvider extends OpenIDConnectProvider
{
    public function __construct($axysConfig)
    {
        $signer = new Sha256();
        $optionProvider = new HttpBasicAuthOptionProvider();
        $baseUrl = $axysConfig["base_url"] ?? "https://axys.me";

        parent::__construct(
            [
                "clientId" => $axysConfig["client_id"],
                "clientSecret" => $axysConfig["client_secret"],
                "redirectUri" => $axysConfig["redirect_uri"],
                "urlAuthorize" => "$baseUrl/openid/authorize",
                "urlAccessToken" => "$baseUrl/api/access_token",
                "urlResourceOwnerDetails" => "$baseUrl/oauth/resource",
                "idTokenIssuer" => "https://axys.me",
                "publicKey" => self::_getPublicKey(),
            ],
            [
                "signer" => $signer,
                "optionProvider" => $optionProvider
            ]
        );
    }

    private static function _getPublicKey(): string
    {
        return <<<EOD
-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA0P+PLgleYMJiMLPKia6s                          
lnlzXScN4kUzkRqIMfxxD+FfIJQ14iR3bRxLtob95Zn0qOMwfkTKDCKQeETAyEz7                          
W+bx1SRBPXR7mjFohwgzpMs6/4LEtEW2ub73n2g7q2zUyNNXnToWk9uKmILFIAzb                          
sgVprFI69GS47IwGZqFSFXGLn9ixXLA2XXYxZdxjE/WZmrDU7n0A9/dYgtIzq93Y                          
BE+C9clMg/ssQAtQ20HoJxsJPPkMmF1CTxe/1OJG9aD3sUG0X/xFUso1aRXsI87a                          
szZ5JXu+Tjlj0sdcc0/poOue9nNbARw2l7Nh7Jas8G2wIs63cC+RskCz4mn4LDac                          
ZAyFfLI5kyeNpVSV+ZKWG1vqltLg35PyqLCrD0EOMSCa/XbcKauj2pDTSmABkBRT                          
WhAyIN5LR+gml00uNPdvB2FPSXWZLTtnivJJ6xzIPD15V8nbj/n9pV6mrqyp4wtH                          
muywwYfFVmByYfuMPk+wj2MUhsCkITl4oOCR6LAnJZB5Ec+8CohJd4xQH9BRRJul                          
34YYmsn3pa9Dze9GkKLsR+mBaFmTlVTKQYR9kraMJijDjrQuH+v/xJsUptCzOXa4                          
US9gvXGO7Uvyc+0DU0xQ+N9e1p6VyyuouPotRzmCE0LDhw8ezloR+5PpqG5blPIz                          
om7SomHqOP92U9L2Pl3ttBMCAwEAAQ==    
-----END PUBLIC KEY-----
EOD;
    }
}
<?php

namespace Silassena\FullPay;

class AsaasGateway extends GatewayPay
{
    function __construct(string $baseUrl, protected string $token)
    {
        parent::__construct($baseUrl);

        $this->setHeader(["access_token" => $this->token]);
    }

    public function post(string $uri, array $payload): array
    {
       
        $this->setPayload($payload);

        $response = $this->postRequest($uri);

        return $response;

    }
    
    public function createAssinaturaRecorrente(): array
    {
        return [];
    }
}

<?php

namespace Silassena\FullPay;

abstract class GatewayPay extends Gateway implements ContractGatewayPay
{
    
    abstract public function createAssinaturaRecorrente(): array;
}
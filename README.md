# FullPay

Biblioteca PHP para integração com gateways de pagamento.

> ⚠️ Projeto em desenvolvimento inicial. A API pode mudar entre versões.

## Requisitos

- PHP 8.3 ou superior
- Composer

## Instalação

```bash
composer require silassena/full-pay
```

## O que tem por enquanto

### Gateways suportados

| Gateway | Classe |
|---|---|
| [Asaas](https://www.asaas.com) | `Silassena\FullPay\AsaasGateway` |

### Asaas

Requisições `POST` para qualquer endpoint da API do Asaas, autenticadas pela chave de API.

```php
use Silassena\FullPay\AsaasGateway;

$asaas = new AsaasGateway('https://api-sandbox.asaas.com/v3', 'SUA_CHAVE_DE_API');

$resultado = $asaas->post('customers', [
    'name'    => 'João da Silva',
    'cpfCnpj' => '24971563792',
]);

$resultado['status'];   // código HTTP, ex.: 200
$resultado['response']; // corpo da resposta como array
```

- Ambiente de testes: `https://api-sandbox.asaas.com/v3`
- Produção: `https://api.asaas.com/v3`

> Informe o endpoint **sem barra no início** (`customers`, não `/customers`).

### Retorno

Todas as requisições retornam um array no formato:

```php
['status' => int, 'response' => array|null]
```

Falhas de conexão retornam `status` `503`, com a mensagem em `response['error']`.

## Licença

MIT

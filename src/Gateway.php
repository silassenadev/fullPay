<?php

declare(strict_types=1);

namespace Silassena\FullPay;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;

abstract class Gateway
{
    /**
     * @var Client $http
     */
    private Client $http;

    /**
     * @var string
     */
    private string $accept;

    /**
     * @var string
     */
    private string $contentType;

    /**
     * @var array $headers
     */
    private array $headers;

    /**
     * @var array $payload
     */
    private array $payload;

    /**
     * @param string $baseUrl
     */
    public function __construct(protected string $baseUrl)
    {
        $this->http = new Client(['base_uri' => rtrim($this->baseUrl, '/') . '/', 'timeout' => 30, 'http_errors' => false]);
        $this->accept = 'application/json';
        $this->contentType = 'application/json';
        $this->headers = [];
        $this->payload = [];

        $this->defaultHeaders();
    }

    /**
     * 
     * @return array
     */
    public function getHeader(): array
    {
        return $this->headers;
    }

    /**
     * @param array $header
     * 
     * @return self
     */
    public function setHeader(array $header): self
    {
        $this->headers = array_merge($this->headers, $header);

        return $this;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @param array $payload
     * 
     * @return self
     */
    public function setPayload(array $payload): self
    {
        $this->payload = array_merge($this->payload, $payload);

        return $this;
    }

    /**
     * @param string $accept
     * 
     * @return self
     */
    public function setAccept(string $accept): self
    {
        $this->accept = $accept;

        $this->setHeader(['Accept' => $accept]);

        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getAccept(): string
    {
        return $this->accept;
    }

    /**
     * @param string $contentType
     * 
     * @return self
     */
    public function setContentType(string $contentType): self
    {
        $this->contentType = $contentType;

        $this->setHeader(['Content-Type' => $this->contentType]);

        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    private function defaultHeaders(): void
    {
        $this->setHeader([
            'Accept' => $this->accept,
            'Content-Type' => $this->contentType
        ]);
    }

    /**
     * @param string $uri
     * @param array $query
     * 
     * @return array
     */
    public function getRequest(string $uri, array $query = []): array
    {
        try {
            $response = $this->http->get($uri, ["headers" => $this->headers, "query" => $query]);
            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);

            return ['status' => $statusCode, 'response' => $body];
        } catch (ConnectException $e) {
            return ['status' => 503, 'response' => ['error' => $e->getMessage()]];
        } catch (GuzzleException $e) {
            return ['status' => 0, 'response' => ['error' => $e->getMessage()]];
        }
    }

    /**
     * @param string $uri
     * 
     * @return array
     */
    public function postRequest(string $uri): array
    {
        try {
            $response = $this->http->post($uri, [
                'headers' => $this->headers,
                'json' => $this->payload
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);
            return ['status' => $statusCode, 'response' => $body];
        } catch (ConnectException $e) {
            return ['status' => 503, 'response' => ['error' => $e->getMessage()]];
        } catch (GuzzleException $e) {
            return ['status' => 0, 'response' => ['error' => $e->getMessage()]];
        } finally {
            $this->resetPayload();
        }
    }

    /**
     * @param string $uri
     * 
     * @return array
     */
    public function deleteRequest(string $uri): array
    {
        try {
            $response = $this->http->delete($uri, [
                'headers' => $this->headers,
                'json' => $this->payload
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);
            return ['status' => $statusCode, 'response' => $body];
        } catch (ConnectException $e) {
            return ['status' => 503, 'response' => ['error' => $e->getMessage()]];
        } catch (GuzzleException $e) {
            return ['status' => 0, 'response' => ['error' => $e->getMessage()]];
        } finally {
            $this->resetPayload();
        }
    }

    private function resetPayload(): void
    {
        $this->payload = [];
    }
}

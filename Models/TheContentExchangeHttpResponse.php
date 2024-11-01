<?php


namespace TheContentExchange\Models;

/**
 * Class TheContentExchangeHttpResponse
 * @package TheContentExchange\Models
 */
class TheContentExchangeHttpResponse
{
    /**
     * @var string[]
     */
    private $headers;

    /**
     * @var mixed
     */
    private $body;

    /**
     * @var int
     */
    private $responseCode;

    /**
     * @var string
     */
    private $responseMessage;

    /**
     * TheContentExchangeHttpResponse constructor.
     *
     * @param int $responseCode
     * @param string $responseMessage
     * @param string[] $headers
     * @param mixed $body
     */
    public function __construct(int $responseCode, string $responseMessage = "", array $headers = [], $body = null)
    {
        $this->headers = $headers;
        $this->body = $body;
        $this->responseCode = $responseCode;
        $this->responseMessage = $responseMessage;
    }

    /**
     * @return string[]
     */
    public function tceGetHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function tceGetBody()
    {
        return $this->body;
    }

    /**
     * @return int
     */
    public function tceGetResponseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * @return string
     */
    public function tceGetResponseMessage(): string
    {
        return $this->responseMessage;
    }
}

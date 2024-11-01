<?php


namespace TheContentExchange\Services\TCE;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use TheContentExchange\WpWrappers\WpData\TheContentExchangeWpOptionsWrapper;
use TheContentExchange\WpWrappers\TheContentExchangeWpWrapperFactory;

/**
 * Class TheContentExchangeSessionService
 *
 * @package TheContentExchange\Services\TCE
 */
class TheContentExchangeSessionService
{

    public const THE_CONTENT_EXCHANGE_AWS_REGION = THE_CONTENT_EXCHANGE_AWS_REGION;

    public const THE_CONTENT_EXCHANGE_AWS_USER_POOL = THE_CONTENT_EXCHANGE_AWS_USER_POOL;

    public const THE_CONTENT_EXCHANGE_AWS_USER_POOL_WEBCLIENT = THE_CONTENT_EXCHANGE_AWS_USER_POOL_WEBCLIENT;

    public const THE_CONTENT_EXCHANGE_AWS_IDENTITY_POOL = THE_CONTENT_EXCHANGE_AWS_IDENTITY_POOL;

    public const THE_CONTENT_EXCHANGE_API_ENDPOINT = THE_CONTENT_EXCHANGE_API_ENDPOINT;

    public const THE_CONTENT_EXCHANGE_UPLOAD_PATH = THE_CONTENT_EXCHANGE_UPLOAD_PATH;

    /**
     * @var TheContentExchangeWpOptionsWrapper
     */
    private $wpOptionsWrapper;

    /**
     * @var CognitoIdentityProviderClient
     */
    private $identityProviderClient;

    /**
     * TheContentExchangeSessionService constructor.
     *
     * @param TheContentExchangeWpWrapperFactory $wpWrapperFactory
     */
    public function __construct(TheContentExchangeWpWrapperFactory $wpWrapperFactory)
    {
        $this->wpOptionsWrapper = $wpWrapperFactory->tceCreateWpOptionsWrapper();
        $this->identityProviderClient = new CognitoIdentityProviderClient([
          'region' => self::THE_CONTENT_EXCHANGE_AWS_REGION,
          'version' => 'latest',
          'credentials' => false,
        ]);
    }

    /**
     * Init TCE session options.
     */
    public function tceInitSessionOptions(): void
    {
        $this->wpOptionsWrapper->tceInitOption('accessCode');
        $this->wpOptionsWrapper->tceInitOption('idToken');
        $this->wpOptionsWrapper->tceInitOption('refreshToken');
    }

    /**
     * Update the 'accessCode'.
     *
     * @param string $code
     */
    public function tceUpdateAccessCode(string $code): void
    {
        $this->wpOptionsWrapper->tceUpdateOptionValue('accessCode', $code);
    }

    /**
     * Update the 'idToken'.
     *
     * @param string $token
     */
    public function tceUpdateIdToken(string $token): void
    {
        $this->wpOptionsWrapper->tceUpdateOptionValue('idToken', $token);
    }

    /**
     * Update the 'refreshToken'.
     *
     * @param string $token
     */
    public function tceUpdateRefreshToken(string $token): void
    {
        $this->wpOptionsWrapper->tceUpdateOptionValue('refreshToken', $token);
    }

    /**
     * Get the 'accessCode'.
     *
     * @return string
     */
    public function tceGetAccessCode(): string
    {
        return $this->wpOptionsWrapper->tceGetOptionValue('accessCode');
    }

    /**
     * Get the 'idToken'.
     *
     * @return string
     */
    public function tceGetIdToken(): string
    {
        return $this->wpOptionsWrapper->tceGetOptionValue('idToken');
    }

    /**
     * Get the 'refreshToken'.
     *
     * @return string
     */
    public function tceGetRefreshToken(): string
    {
        return $this->wpOptionsWrapper->tceGetOptionValue('refreshToken');
    }

    /**
     * Delete TCE session options.
     */
    public function tceDeleteSessionOptions(): void
    {
        $this->wpOptionsWrapper->tceDeleteOption('accessCode');
        $this->wpOptionsWrapper->tceDeleteOption('idToken');
        $this->wpOptionsWrapper->tceDeleteOption('refreshToken');
    }

    /**
     * Check whether the connection is still active by checking if the refreshToken exists.
     *
     * @return bool
     */
    public function tceIsConnected(): bool
    {
        return !empty($this->tceGetRefreshToken());
    }

    /**
     * Create new JWT tokens.
     */
    public function tceGetNewJwtTokens(): void
    {
        $auth = $this->identityProviderClient->InitiateAuth([
          'ClientId' => self::THE_CONTENT_EXCHANGE_AWS_USER_POOL_WEBCLIENT,
          'AuthFlow' => 'REFRESH_TOKEN_AUTH',
          'AuthParameters' => [
            'REFRESH_TOKEN' => $this->tceGetRefreshToken(),
          ],
        ]);

        $tokens = $auth->get('AuthenticationResult');
        $this->tceUpdateIdToken($tokens['IdToken']);
        $this->tceUpdateAccessCode($tokens['AccessToken']);
    }
}

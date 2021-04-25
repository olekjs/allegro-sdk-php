<?php

namespace Olekjs\Allegro\Authentication\CodeFlow;

use GuzzleHttp\Exception\RequestException;
use Olekjs\Allegro\Client;
use Olekjs\Allegro\Contracts\Auth as AuthContract;

class Auth implements AuthContract
{
    /**
     * Client identificator.
     *
     * @var  string
     */
    private $clientId;

    /**
     * Client secret code.
     *
     * @var  string
     */
    private $clientSecret;

    /**
     * Link that after successful authentication
     * redirects the user to the application along with the authorization code
     *
     * @var  string
     */
    private $redirectUri;

    /**
     * Determining if the environment is sandbox.
     *
     * @var  boolean
     */
    private $sandboxEnviroment;

    /**
     * Random character string to secure PKCE authorization.
     *
     * @var  string
     */
    private $codeVerifier = null;

    /**
     * Auth constructor.
     *
     * @param  string  $clientId
     * @param  string  $clientSecret
     * @param  string  $redirectUri
     * @param  boolean  $useProofKeyCodeExchange
     * @param  boolean  $sandboxEnviroment
     */
    public function __construct(
        string $clientId,
        string $clientSecret,
        string $redirectUri,
        bool $useProofKeyCodeExchange = true,
        bool $sandboxEnviroment = false
    ) {
        $this->clientId          = $clientId;
        $this->clientSecret      = $clientSecret;
        $this->redirectUri       = $redirectUri;
        $this->sandboxEnviroment = $sandboxEnviroment;

        if ($useProofKeyCodeExchange) {
            $randomString = bin2hex(openssl_random_pseudo_bytes(32));
            $codeVerifier = base64_url_encode(pack('H*', $randomString));

            $this->codeVerifier = $codeVerifier;
        }
    }

    /**
     * Get link to authentication page.
     *
     * @param  array  $scopes
     * @param  string|null  $state
     * @param  string|null  $prompt
     *
     * @return string
     */
    public function getAuthenticationLink(
        array $scopes = [],
            ? string $state = null,
            ? string $prompt = null
    ) : string{
        $data = [
            'response_type' => 'code',
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
        ];

        if (!empty($scopes)) {
            $data['scope'] = implode(' ', $scopes);
        }

        if (!empty($state) && $state !== null) {
            $data['state'] = $state;
        }

        if (!empty($prompt) && $prompt !== null) {
            $data['prompt'] = $prompt;
        }

        if ($this->codeVerifier !== null) {
            $data['code_challenge_method'] = 'S256';
            $data['code_challenge']        = $this->getGeneratedCodeChallenge();
        }

        return $this->getAuthorizationUrl() . 'authorize?' . http_build_query($data);
    }

    /**
     * Authorize user.
     *
     * @param  string  $code
     * @param  string|null  $codeVerifier
     *
     * @return array
     * @throws  RequestException
     */
    public function authorize(string $code,  ? string $codeVerifier = null) : array
    {
        $data = [
            'query' => [
                'grant_type'   => 'authorization_code',
                'code'         => $code,
                'redirect_uri' => $this->redirectUri,
            ],
        ];

        if ($codeVerifier !== null) {
            $data['query']['code_verifier'] = $codeVerifier;
        } else {
            $data['headers'] = [
                'Authorization' => 'Basic ' . base64_encode(sprintf('%s:%s', $this->clientId, $this->clientSecret)),
            ];
        }

        try {
            $response = $this->getClient()->post('token', $data);

            return $response->getBody()->getContents();
        } catch (RequestException $exception) {
            $response = $exception->getResponse();

            return [
                'message' => $response->getReasonPhrase(),
                'status'  => $response->getStatusCode(),
            ];
        }
    }

    /**
     * Refresh access token.
     *
     * @param  string  $refreshToken
     *
     * @return array
     * @throws  RequestException
     */
    public function refreshToken(string $refreshToken) : array
    {
        $data = [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(sprintf('%s:%s', $this->clientId, $this->clientSecret)),
            ],
            'query'   => [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $refreshToken,
                'redirect_uri'  => $this->redirectUri,
            ],
        ];

        try {
            $response = $this->getClient()->post('token', $data);

            return $response->getBody()->getContents();
        } catch (RequestException $exception) {
            $response = $exception->getResponse();

            return [
                'message' => $response->getReasonPhrase(),
                'status'  => $response->getStatusCode(),
            ];
        }
    }

    /**
     * Get code verifier.
     *
     * @return string
     */
    public function getCodeVerifier(): string
    {
        return $this->codeVerifier;
    }

    /**
     * Get Allegro http client.
     *
     * @return Olekjs\Allegro\Client
     */
    private function getClient(): Client
    {
        return new Client([
            'base_uri' => $this->getAuthorizationUrl(),
        ]);
    }

    /**
     * Get authorization url depending on environment.
     *
     * @return string
     */
    private function getAuthorizationUrl(): string
    {
        if ($this->sandboxEnviroment) {
            return config('app.sandbox.authorization_url');
        }

        return config('app.production.authorization_url');
    }

    /**
     * Get generated code challange using sha256 encrytpion.
     *
     * @return string
     */
    private function getGeneratedCodeChallenge(): string
    {
        return base64_url_encode(pack('H*', hash('sha256', $this->codeVerifier)));
    }
}

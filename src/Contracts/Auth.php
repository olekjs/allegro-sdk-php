<?php

namespace Olekjs\Allegro\Contracts;

interface Auth
{
    /**
     * Get code verifier.
     *
     * @return string
     */
    public function getCodeVerifier(): string;

    /**
     * Refresh access token.
     *
     * @param  string  $refreshToken
     *
     * @return array
     * @throws  RequestException
     */
    public function refreshToken(string $refreshToken): array;

    /**
     * Authorize user.
     *
     * @param  string  $code
     * @param  string|null  $codeVerifier
     *
     * @return array
     * @throws  RequestException
     */
    public function authorize(string $code, ?string $codeVerifier = null) : array;

    /**
     * Get link to authentication page.
     *
     * @param  array  $scopes
     * @param  string|null  $state
     * @param  string|null  $prompt
     *
     * @return string
     */
    public function getAuthenticationLink(array $scopes = [], ?string $state = null, ?string $prompt = null) : string;
}

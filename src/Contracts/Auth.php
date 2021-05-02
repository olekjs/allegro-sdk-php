<?php

namespace Olekjs\Allegro\Contracts;

use Olekjs\Allegro\Responses\Response;

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
     * @return Olekjs\Allegro\Responses\Response\Response
     */
    public function refreshToken(string $refreshToken): Response;

    /**
     * Authorize user.
     *
     * @param  string  $code
     * @param  string|null  $codeVerifier
     *
     * @return Olekjs\Allegro\Responses\Response\Response
     */
    public function authorize(string $code, ?string $codeVerifier = null) : Response;

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

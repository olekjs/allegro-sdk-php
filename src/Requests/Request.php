<?php

namespace Olekjs\Allegro\Requests;

use Olekjs\Allegro\Client;
use Olekjs\Allegro\Requests\Get;

class Request
{
    /**
     * Path to request class files.
     *
     * @var  string
     */
    const REQUEST_CLASSES_PATH = 'Olekjs\\Allegro\\Requests\\';

    /**
     * Access token used to authorize requests.
     *
     * @var  string
     */
    private $accessToken;

    /**
     * Request constructor.
     *
     * @param  string  $accessToken
     */
    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Detect request type and method name then do request
     *
     * @param  string  $name
     * @param  array  $arguments
     *
     * @return miexd
     */
    public function __call(string $name, array $arguments)
    {
        $explodedMethodName = preg_split('/(?=[A-Z])/', $name);
        $requestType        = $explodedMethodName[0];

        unset($explodedMethodName[0]);

        $methodName = implode('', $explodedMethodName);
        $endpoint   = config("endpoints.{$methodName}");

        $className     = self::REQUEST_CLASSES_PATH . ucwords($requestType);
        $classInstance = new $className($this->getClient(), $endpoint, $arguments);

        return $classInstance->doRequest();
    }

    /**
     * Get Allegro http client.
     *
     * @return Olekjs\Allegro\Client
     */
    private function getClient(): Client
    {
        return new Client([
            'base_uri' => $this->getBaseUri(),
            'headers'  => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Accept'        => 'application/vnd.allegro.public.v1+json',
            ],
        ]);
    }

    /**
     * Get base uri depending on environment.
     *
     * @return string
     */
    private function getBaseUri(): string
    {
        if (true) {
            return config('app.sandbox.api_url');
        }

        return config('app.production.api_url');
    }
}

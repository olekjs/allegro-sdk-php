<?php

namespace Olekjs\Allegro\Requests;

use InvalidArgumentException;
use Olekjs\Allegro\Client;
use Olekjs\Allegro\Requests\Get;
use UnexpectedValueException;

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


        if ($endpoint === null) {
            throw new UnexpectedValueException("An endpoint with this name was not found [{$name}]");
        }

        $extractedArguments = str_between($endpoint, '{', '}');

        $className = self::REQUEST_CLASSES_PATH . ucwords($requestType);

        $classInstance = new $className(
            $this->getClient(),
            $this->fillEndpointWithArguments($extractedArguments, $arguments, $endpoint),
            $this->getQueryParameters($extractedArguments, $arguments)
        );

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
        /*
        @TODO WIP
         */
        if (true) {
            return config('app.sandbox.api_url');
        }

        return config('app.production.api_url');
    }

    /**
     * Fill the endpoint with the required arguments passed in the method parameters
     *
     * @param  array  $extractedArguments
     * @param  array  $arguments
     * @param  string  $endpoint
     *
     * @return mixed
     */
    private function fillEndpointWithArguments(array $extractedArguments, array $arguments, string $endpoint)
    {
        if (
            $this->hasRequiredArguments($extractedArguments, $arguments) &&
            count($extractedArguments) > count($arguments)
        ) {
            $requiredArguments = implode(', ', $extractedArguments[1]);

            throw new InvalidArgumentException("Too few arguments. Required arguments: {$requiredArguments}");
        }

        return str_replace($extractedArguments[0], $arguments, $endpoint);
    }

    /**
     * Take optional arguments from the parameters of the method
     *
     * @param  array  $extractedArguments
     * @param  array  $arguments
     *
     * @return extractedArguments
     */
    private function getQueryParameters(array $extractedArguments, array $arguments)
    {
        if ($this->hasRequiredArguments($extractedArguments, $arguments)) {
            return $arguments[count($extractedArguments[1])];
        }

        return $arguments[0];
    }

    /**
     * Check if the endpoint contains the required arguments
     *
     * @param  array  $extractedArguments
     *
     * @return bool
     */
    private function hasRequiredArguments(array $extractedArguments): bool
    {
        return !empty($extractedArguments[0]) && !empty($extractedArguments[1]);
    }
}

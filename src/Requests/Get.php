<?php

namespace Olekjs\Allegro\Requests;

use GuzzleHttp\Exception\RequestException;
use Olekjs\Allegro\Client;
use Olekjs\Allegro\Responses\Response;

class Get
{
    /**
     * Allegro http client.
     *
     * @var  Olekjs\Allegro\Client
     */
    private $client;

    /**
     * Endpoint to execute the query.
     *
     * @var  string
     */
    private $endpoint;

    /**
     * Parameters passed to the query.
     *
     * @var  array
     */
    private $queryParams;

    /**
     * Get request constructor.
     *
     * @param  Olekjs\Allegro\Client  $client
     * @param  string  $endpoint
     * @param  array|null  $queryParams
     */
    public function __construct(Client $client, string $endpoint,  ?array $queryParams)
    {
        $this->client      = $client;
        $this->endpoint    = $endpoint;
        $this->queryParams = $queryParams;
    }

    /**
     * Execute query and return response.
     *
     * @return Olekjs\Allegro\Responses\Response
     */
    public function doRequest() : Response
    {
        try {
            $response = $this->client->get(
                $this->endpoint,
                [
                    'query' => $this->queryParams,
                ]
            );

            return Response::create(
                json_decode($response->getBody()->getContents(), true)
            );
        } catch (RequestException $exception) {
            $response = $exception->getResponse();

            return Response::create([
                'message' => $response->getReasonPhrase(),
                'status'  => $response->getStatusCode(),
            ]);
        }
    }
}

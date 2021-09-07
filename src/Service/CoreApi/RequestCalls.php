<?php


namespace App\Service\CoreApi;

use App\Entity\Assistants;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestCalls
{
    const BASE_PATH = 'https://assistant.clinitick.com';

    private $client;
    private $user;

    /**
     * RequestCalls constructor.
     *
     * @required
     *
     * @param HttpClientInterface $client
     * @param Assistants $assistant
     */
    public function __construct(HttpClientInterface $client, Assistants $assistant)
    {
        $this->client = $client;
        $this->user = $assistant;
    }

    public function doRequestWithoutToken(string $requestType, string $address, array $postData = null)
    {
        $response = $this->client->request($requestType, self::BASE_PATH . $address, empty($postData) ? [] : ['json' => $postData]);

        return [
            'code' => $response->getStatusCode(),
            'data' => $response->getContent(false),
        ];
    }

    public function doRequest(string $requestType, string $address, array $postData = null)
    {
        $response = $this->client->request($requestType, self::BASE_PATH . $address,
            empty($postData) ? [
                'headers' => [
                    'token' => $this->user->getToken()
                ]
            ] : [
                'headers' => [
                    'token' => $this->user->getToken()
                ],
                'json' => $postData
            ]);

        return [
            'code' => $response->getStatusCode(),
            'data' => $response->getContent(false),
        ];
    }
}
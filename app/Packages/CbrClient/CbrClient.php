<?php

namespace App\Packages\CbrClient;

use App\Packages\CbrClient\DTO\DailyRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use SimpleXMLElement;
use Throwable;

class CbrClient
{
    private Client $client;

    public function __construct(string $baseUrl = 'http://www.cbr.ru')
    {
        $this->client = new Client([
            'base_uri' => $baseUrl,
        ]);
    }

    /**
     * @throws GuzzleException
     * @throws Throwable
     */
    public function getCourses(DailyRequest $request): SimpleXMLElement
    {
        $uri = "/scripts/XML_daily.asp?date_req={$request->getDate()}";

        try {
            $data = $this->client->get($uri, [RequestOptions::CONNECT_TIMEOUT => 10]);
        } catch (Throwable $e) {
            throw $e;
        }

        return (new SimpleXMLElement($data->getBody()->getContents()));
    }
}

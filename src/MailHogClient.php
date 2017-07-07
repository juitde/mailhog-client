<?php

declare(strict_types=1);

namespace JUIT\MailHog;

use GuzzleHttp\Client as HttpClient;

class MailHogClient
{
    /** @var HttpClient */
    private $httpClient;

    public static function create(string $mailHogHost = 'http://localhost:8025'): MailHogClient
    {
        $httpClient = new HttpClient(['base_uri' => $mailHogHost . '/api/']);

        return new static($httpClient);
    }

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function deleteAll()
    {
        $this->httpClient->request('DELETE', 'v1/messages');
    }

    /** @return Message[] */
    public function fetchAll(): array
    {
        $response = $this->httpClient->request('GET', 'v2/messages');
        $data     = json_decode($response->getBody()->getContents(), true);
        $messages = [];
        foreach ($data['items'] as $item) {
            $messages[] = Message::create($item);
        }

        return $messages;
    }
}

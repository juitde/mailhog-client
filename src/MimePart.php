<?php

declare(strict_types=1);

namespace JUIT\MailHog;

use Symfony\Component\HttpFoundation\HeaderBag;

class MimePart
{
    /** @var HeaderBag */
    private $headers;

    /** @var string */
    private $body;

    public static function create(array $rawData): MimePart
    {
        return new static(
            $rawData['Headers'],
            $rawData['Body']
        );
    }

    public function __construct(array $headers, string $body)
    {
        $this->headers = new HeaderBag($headers);
        $this->body    = $body;
    }

    /** @return string|null */
    public function getContentType()
    {
        return $this->headers->get('Content-Type');
    }

    public function getBody(): string
    {
        return $this->body;
    }
}

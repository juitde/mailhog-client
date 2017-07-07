<?php

declare(strict_types=1);

namespace JUIT\MailHog;

use Symfony\Component\HttpFoundation\HeaderBag;

class Message
{
    /** @var HeaderBag */
    private $headers;

    /** @var string */
    private $body;

    /** @var MimePart[] */
    private $parts;

    public static function create(array $rawData): Message
    {
        return new static(
            $rawData['Content']['Headers'],
            $rawData['Content']['Body'],
            $rawData['MIME']['Parts'] ?? []
        );
    }

    public function __construct(array $headers, string $body, array $parts = [])
    {
        $this->headers = new HeaderBag($headers);
        $this->body    = $body;
        $this->parts   = array_map(function ($data) {
            return MimePart::create($data);
        }, $parts);
    }

    public function getFrom(): string
    {
        return $this->headers->get('From');
    }

    public function getTo(): string
    {
        return $this->headers->get('To');
    }

    public function getSubject(): string
    {
        return $this->headers->get('Subject');
    }

    public function getBody(): string
    {
        return $this->body;
    }

    /** @return MimePart|null */
    public function getPlainPart()
    {
        return $this->getMimePart('text/plain');
    }

    /** @return MimePart|null */
    public function getHtmlPart()
    {
        return $this->getMimePart('text/html');
    }

    /** @return MimePart|null */
    private function getMimePart(string $contentType)
    {
        foreach ($this->parts as $part) {
            if (0 === strpos((string) $part->getContentType(), $contentType)) {
                return $part;
            }
        }

        return null;
    }
}

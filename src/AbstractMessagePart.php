<?php

declare(strict_types=1);

namespace JUIT\MailHog;

use Symfony\Component\HttpFoundation\HeaderBag;

abstract class AbstractMessagePart
{
    /** @var HeaderBag */
    protected $headers;

    /** @var MimePart[] */
    protected $parts;

    /** @var string */
    protected $body;

    public function __construct(array $headers, string $body, array $parts = [])
    {
        $this->headers = new HeaderBag($headers);
        $this->body    = $body;
        $this->parts   = array_map(
            function ($data) {
                return MimePart::create($data);
            },
            $parts
        );
    }

    /** @return string|null */
    public function getContentType()
    {
        return $this->headers->get('Content-Type');
    }

    public function getBody(): string
    {
        if ($this->contentTypeStartsWith('multipart')) {
            return $this->body;
        }

        $decoded = quoted_printable_decode($this->body);

        return str_replace("\r\n", "\n", $decoded);
    }

    /** @return MimePart|null */
    protected function getMimePart(string $contentType)
    {
        foreach ($this->parts as $part) {
            if ($part->contentTypeStartsWith($contentType)) {
                return $part;
            }
            if ($subPart = $part->getMimePart($contentType)) {
                return $subPart;
            }
        }

        return null;
    }

    protected function contentTypeStartsWith(string $needle): bool
    {
        return 0 === strpos((string) $this->getContentType(), $needle);
    }
}

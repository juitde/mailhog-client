<?php

declare(strict_types=1);

namespace JUIT\MailHog;

use Symfony\Component\HttpFoundation\HeaderBag;

class Message extends AbstractMessagePart
{
    public static function create(array $rawData): Message
    {
        return new static(
            new HeaderBag($rawData['Content']['Headers']),
            $rawData['Content']['Body'],
            $rawData['MIME']['Parts'] ?? []
        );
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
}

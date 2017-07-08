<?php

declare(strict_types=1);

namespace JUIT\MailHog;

class Attachment extends AbstractMessagePart
{
    public static function createFromMimePart(MimePart $mimePart): Attachment
    {
        return new static(
            $mimePart->headers,
            $mimePart->body,
            $mimePart->parts
        );
    }

    public function getFilename(): string
    {
        $header = $this->headers->get('Content-Disposition');

        return substr($header, strrpos($header, '=') + 1);
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getContent()
    {
        return base64_decode($this->body);
    }
}

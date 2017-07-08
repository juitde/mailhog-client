<?php

declare(strict_types=1);

namespace JUIT\MailHog;

use Symfony\Component\HttpFoundation\HeaderBag;

class MimePart extends AbstractMessagePart
{
    public static function create(array $rawData): MimePart
    {
        return new static(
            new HeaderBag($rawData['Headers']),
            $rawData['Body'],
            $rawData['MIME']['Parts'] ?? []
        );
    }

    public function isAttachment(): bool
    {
        return $this->headerStartsWith('Content-Disposition', 'attachment');
    }
}

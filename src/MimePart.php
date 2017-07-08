<?php

declare(strict_types=1);

namespace JUIT\MailHog;

class MimePart extends AbstractMessagePart
{
    public static function create(array $rawData): MimePart
    {
        return new static(
            $rawData['Headers'],
            $rawData['Body'],
            $rawData['MIME']['Parts'] ?? []
        );
    }
}

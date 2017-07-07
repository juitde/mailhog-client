<?php

declare(strict_types=1);

namespace JUIT\Tests\MailHog;

use JUIT\MailHog\MimePart;
use PHPUnit\Framework\TestCase;

class MimePartTest extends TestCase
{
    /** @test */
    public function it_creates_an_instance_from_raw_data()
    {
        $SUT = MimePart::create([
            'Headers' => [
                'Content-Type' => [
                    'text/plain; charset=utf-8',
                ],
            ],
            'Body' => 'Some plain part',
        ]);

        $this->assertSame('text/plain; charset=utf-8', $SUT->getContentType());
        $this->assertSame('Some plain part', $SUT->getBody());
    }
}

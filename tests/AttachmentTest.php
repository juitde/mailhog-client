<?php

declare(strict_types=1);

namespace JUIT\Tests\MailHog;

use JUIT\MailHog\Attachment;
use JUIT\MailHog\MimePart;

class AttachmentTest extends MailHogTestCase
{
    /** @test */
    public function it_creates_an_instance_from_a_mime_part()
    {
        $SUT = $this->createSUT();

        $this->assertSame('application/octet-stream; name=some_file.txt', $SUT->getContentType());
    }

    /** @test */
    public function it_does_not_decode_the_body()
    {
        $SUT = $this->createSUT();

        $this->assertSame('U29tZSBhdHRhY2htZW50IGRhdGE=', $SUT->getBody());
    }

    /** @test */
    public function it_returns_the_filename()
    {
        $SUT = $this->createSUT();

        $this->assertSame('some_file.txt', $SUT->getFilename());
    }

    /** @test */
    public function it_returns_the_file_content()
    {
        $SUT = $this->createSUT();

        $this->assertSame('Some attachment data', $SUT->getContent());
    }

    private function createSUT(): Attachment
    {
        $mimePart = MimePart::create(
            [
                'Headers' => [
                    'Content-Disposition' => [
                        'attachment; filename=some_file.txt',
                    ],
                    'Content-Type'        => [
                        'application/octet-stream; name=some_file.txt',
                    ],
                ],
                'Body'    => 'U29tZSBhdHRhY2htZW50IGRhdGE=',
            ]
        );

        return Attachment::createFromMimePart($mimePart);
    }
}

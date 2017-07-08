<?php

declare(strict_types=1);

namespace JUIT\Tests\MailHog;

use JUIT\MailHog\MimePart;
use PHPUnit\Framework\TestCase;

class MimePartTest extends MailHogTestCase
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

    /** @test */
    public function it_decodes_the_body()
    {
        $SUT = MimePart::create([
            'Headers' => [
                'Content-Type' => [
                    'text/plain; charset=utf-8',
                ],
            ],
            'Body' => "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy ei=\r\nrmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam volu=\r\nptua.\r\nAt vero eos et accusam et justo duo dolores et ea rebum. Stet clita=\r\n kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lo=\r\nrem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirm=\r\nod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam volupt=\r\nua.\r\nAt vero eos et accusam et justo duo dolores et ea rebum. Stet clita k=\r\nasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.",
        ]);

        $this->assertSame('text/plain; charset=utf-8', $SUT->getContentType());
        $this->assertStringEqualsFile(
            $this->getFixturesPath('plain_with_long_text_expected.txt'),
            $SUT->getBody()
        );
    }

    /** @test */
    public function it_does_not_decode_the_body_if_it_is_a_multipart_alternative()
    {
        $SUT = MimePart::create(
            [
                'Headers' => [
                    'Content-Type' => [
                        "multipart/alternative; boundary=\"_=_swift_v4_1499513469_5f26d6ecf30d607307d71ca8aa025a42_=_\"",
                    ],
                ],
                'Body'    => "\r\n--_=_swift_v4_1499513469_5f26d6ecf30d607307d71ca8aa025a42_=_\r\nContent-Type: text\/plain; charset=utf-8\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\nSome plain part\r\n\r\n--_=_swift_v4_1499513469_5f26d6ecf30d607307d71ca8aa025a42_=_\r\nContent-Type: text\/html; charset=utf-8\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n<p>Some HTML part<\/p>\r\n\r\n--_=_swift_v4_1499513469_5f26d6ecf30d607307d71ca8aa025a42_=_--",
            ]
        );

        $this->assertSame(
            "multipart/alternative; boundary=\"_=_swift_v4_1499513469_5f26d6ecf30d607307d71ca8aa025a42_=_\"",
            $SUT->getContentType()
        );
        $this->assertSame(
            "\r\n--_=_swift_v4_1499513469_5f26d6ecf30d607307d71ca8aa025a42_=_\r\nContent-Type: text\/plain; charset=utf-8\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\nSome plain part\r\n\r\n--_=_swift_v4_1499513469_5f26d6ecf30d607307d71ca8aa025a42_=_\r\nContent-Type: text\/html; charset=utf-8\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n<p>Some HTML part<\/p>\r\n\r\n--_=_swift_v4_1499513469_5f26d6ecf30d607307d71ca8aa025a42_=_--",
            $SUT->getBody()
        );
    }
}

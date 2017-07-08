<?php

declare(strict_types=1);

namespace JUIT\Tests\MailHog;

use JUIT\MailHog\Message;

class MessageTest extends MailHogTestCase
{
    /** @test */
    public function it_returns_all_relevant_header_data()
    {
        $rawData = $this->loadJsonFixture('plain');

        $SUT = Message::create($rawData);

        $this->assertSame('Sender <sender@example.com>', $SUT->getFrom());
        $this->assertSame('Recipient <recipient@example.com>', $SUT->getTo());
        $this->assertSame('Some plain mail', $SUT->getSubject());
    }

    /** @test */
    public function it_returns_the_body_of_a_plain_message()
    {
        $rawData = $this->loadJsonFixture('plain');

        $SUT = Message::create($rawData);

        $this->assertSame('Some plain body', $SUT->getBody());
    }

    /** @test */
    public function it_decodes_the_body_of_a_plain_message()
    {
        $rawData = $this->loadJsonFixture('plain_with_long_text');

        $SUT = Message::create($rawData);

        $this->assertStringEqualsFile(
            $this->getFixturesPath('plain_with_long_text_expected.txt'),
            $SUT->getBody()
        );
    }

    /** @test */
    public function it_does_not_decode_the_body_of_a_multipart_message()
    {
        $rawData = $this->loadJsonFixture('multipart_with_long_text');

        $SUT = Message::create($rawData);

        $this->assertSame(
            "\r\n--_=_swift_v4_1499514612_3c60818f963bc606db2f84572c5c7749_=_\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\nLorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy ei=\r\nrmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam volu=\r\nptua.\r\nAt vero eos et accusam et justo duo dolores et ea rebum. Stet clita=\r\n kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lo=\r\nrem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirm=\r\nod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam volupt=\r\nua.\r\nAt vero eos et accusam et justo duo dolores et ea rebum. Stet clita k=\r\nasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.\r\n\r\n--_=_swift_v4_1499514612_3c60818f963bc606db2f84572c5c7749_=_\r\nContent-Type: text/html; charset=utf-8\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy=\r\n eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam v=\r\noluptua.</p>\r\n<p>At vero eos et accusam et justo duo dolores et ea rebum. =\r\nStet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor si=\r\nt amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam n=\r\nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed d=\r\niam voluptua.</p>\r\n<p>At vero eos et accusam et justo duo dolores et ea re=\r\nbum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dol=\r\nor sit amet.</p>\r\n\r\n--_=_swift_v4_1499514612_3c60818f963bc606db2f84572c5c7749_=_--\r\n",
            $SUT->getBody()
        );
    }

    /** @test */
    public function it_returns_the_body_of_an_html_message()
    {
        $rawData = $this->loadJsonFixture('html');

        $SUT = Message::create($rawData);

        $this->assertSame('<p>Some HTML body</p>', $SUT->getBody());
    }

    /** @test */
    public function it_decodes_the_body_of_an_html_message()
    {
        $rawData = $this->loadJsonFixture('html_with_long_text');

        $SUT = Message::create($rawData);

        $this->assertStringEqualsFile(
            $this->getFixturesPath('html_with_long_text_expected.html'),
            $SUT->getBody()
        );
    }

    /** @test */
    public function it_returns_null_as_plain_part_if_message_is_not_multipart()
    {
        $rawData = $this->loadJsonFixture('plain');

        $SUT = Message::create($rawData);

        $this->assertNull($SUT->getPlainPart());
    }

    /** @test */
    public function it_returns_null_as_html_part_if_message_is_not_multipart()
    {
        $rawData = $this->loadJsonFixture('plain');

        $SUT = Message::create($rawData);

        $this->assertNull($SUT->getHtmlPart());
    }

    /** @test */
    public function it_returns_the_plain_part_of_a_multipart_message()
    {
        $rawData = $this->loadJsonFixture('multipart');

        $SUT = Message::create($rawData);

        $this->assertSame('Some plain part', $SUT->getPlainPart()->getBody());
    }

    /** @test */
    public function it_decodes_the_plain_part_of_a_multipart_message()
    {
        $rawData = $this->loadJsonFixture('multipart_with_long_text');

        $SUT = Message::create($rawData);

        $this->assertStringEqualsFile(
            $this->getFixturesPath('plain_with_long_text_expected.txt'),
            $SUT->getPlainPart()->getBody()
        );
    }

    /** @test */
    public function it_returns_the_html_part_of_a_multipart_message()
    {
        $rawData = $this->loadJsonFixture('multipart');

        $SUT = Message::create($rawData);

        $this->assertSame('<p>Some HTML part</p>', $SUT->getHtmlPart()->getBody());
    }

    /** @test */
    public function it_decodes_the_html_part_of_a_multipart_message()
    {
        $rawData = $this->loadJsonFixture('multipart_with_long_text');

        $SUT = Message::create($rawData);

        $this->assertStringEqualsFile(
            $this->getFixturesPath('html_with_long_text_expected.html'),
            $SUT->getHtmlPart()->getBody()
        );
    }

    /** @test */
    public function it_returns_the_plain_part_of_a_multipart_message_with_attachment()
    {
        $rawData = $this->loadJsonFixture('multipart_with_attachments');

        $SUT = Message::create($rawData);

        $this->assertSame('Some plain part', $SUT->getPlainPart()->getBody());
    }

    /** @test */
    public function it_returns_the_html_part_of_a_multipart_message_with_attachment()
    {
        $rawData = $this->loadJsonFixture('multipart_with_attachments');

        $SUT = Message::create($rawData);

        $this->assertSame('<p>Some HTML part</p>', $SUT->getHtmlPart()->getBody());
    }

    /** @test */
    public function it_returns_the_attachments()
    {
        $rawData = $this->loadJsonFixture('multipart_with_attachments');

        $SUT         = Message::create($rawData);
        $attachments = $SUT->getAttachments();

        $this->assertCount(2, $attachments);
        $this->assertSame('some_file.txt', $attachments[0]->getFilename());
        $this->assertSame('some_other_file.txt', $attachments[1]->getFilename());
    }
}

<?php

declare(strict_types=1);

namespace JUIT\Tests\MailHog;

use JUIT\MailHog\Message;

class MessageTest extends MailHogTestCase
{
    /** @test */
    public function it_returns_all_relevant_header_data()
    {
        $rawData = $this->loadFixture('plain');

        $SUT = Message::create($rawData);

        $this->assertSame('Sender <sender@example.com>', $SUT->getFrom());
        $this->assertSame('Recipient <recipient@example.com>', $SUT->getTo());
        $this->assertSame('Some plain mail', $SUT->getSubject());
    }

    /** @test */
    public function it_returns_the_body_of_a_plain_message()
    {
        $rawData = $this->loadFixture('plain');

        $SUT = Message::create($rawData);

        $this->assertSame('Some plain body', $SUT->getBody());
    }

    /** @test */
    public function it_decodes_the_body_of_a_plain_message()
    {
        $rawData = $this->loadFixture('plain_with_long_text');

        $SUT = Message::create($rawData);

        $this->assertStringEqualsFile(
            $this->getFixturesPath() . '/plain_with_long_text_expected.txt',
            $SUT->getBody()
        );
    }

    /** @test */
    public function it_returns_the_body_of_an_html_message()
    {
        $rawData = $this->loadFixture('html');

        $SUT = Message::create($rawData);

        $this->assertSame('<p>Some HTML body</p>', $SUT->getBody());
    }

    /** @test */
    public function it_decodes_the_body_of_an_html_message()
    {
        $rawData = $this->loadFixture('html_with_long_text');

        $SUT = Message::create($rawData);

        $this->assertStringEqualsFile(
            $this->getFixturesPath() . '/html_with_long_text_expected.html',
            $SUT->getBody()
        );
    }

    /** @test */
    public function it_returns_null_as_plain_part_if_message_is_not_multipart()
    {
        $rawData = $this->loadFixture('plain');

        $SUT = Message::create($rawData);

        $this->assertNull($SUT->getPlainPart());
    }

    /** @test */
    public function it_returns_null_as_html_part_if_message_is_not_multipart()
    {
        $rawData = $this->loadFixture('plain');

        $SUT = Message::create($rawData);

        $this->assertNull($SUT->getHtmlPart());
    }

    /** @test */
    public function it_returns_the_plain_part_of_a_multipart_message()
    {
        $rawData = $this->loadFixture('multipart');

        $SUT = Message::create($rawData);

        $this->assertSame('Some plain part', $SUT->getPlainPart()->getBody());
    }

    /** @test */
    public function it_returns_the_html_part_of_a_multipart_message()
    {
        $rawData = $this->loadFixture('multipart');

        $SUT = Message::create($rawData);

        $this->assertSame('<p>Some HTML part</p>', $SUT->getHtmlPart()->getBody());
    }

    /** @test */
    public function it_returns_the_plain_part_of_a_multipart_message_with_attachment()
    {
        $rawData = $this->loadFixture('multipart_with_attachments');

        $SUT = Message::create($rawData);

        $this->assertSame('Some plain part', $SUT->getPlainPart()->getBody());
    }

    /** @test */
    public function it_returns_the_html_part_of_a_multipart_message_with_attachment()
    {
        $rawData = $this->loadFixture('multipart_with_attachments');

        $SUT = Message::create($rawData);

        $this->assertSame('<p>Some HTML part</p>', $SUT->getHtmlPart()->getBody());
    }
}

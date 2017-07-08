<?php

declare(strict_types=1);

namespace JUIT\Tests\MailHog;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use JUIT\MailHog\Message;
use JUIT\MailHog\MailHogClient;

class MailHogClientTest extends MailHogTestCase
{
    /** @var HttpClient|\PHPUnit_Framework_MockObject_MockObject */
    private $httpClient;

    /** @var MailHogClient */
    private $SUT;

    protected function setUp()
    {
        parent::setUp();
        $this->httpClient = $this->createMock(HttpClient::class);
        $this->SUT        = new MailHogClient($this->httpClient);
    }

    /** @test */
    public function it_creates_an_instance_of_itself_with_default_configuration()
    {
        $SUT = MailHogClient::create();

        $this->assertInstanceOf(MailHogClient::class, $SUT);

        // Testing the internals of the system unter test is a trade-off here.
        // The intention of the test is to assert that the HTTP client gets a correct base uri.
        // The alternative would have been to implement a getter for the HTTP client.
        // That in turn would have polluted the classes' API.

        $reflectionProperty = new \ReflectionProperty(MailHogClient::class, 'httpClient');
        $reflectionProperty->setAccessible(true);
        /** @var Client $httpClient */
        $httpClient = $reflectionProperty->getValue($SUT);
        /** @var Uri $config */
        $config = $httpClient->getConfig('base_uri');

        $this->assertSame('http://localhost:8025/api/', (string) $config);
    }

    /** @test */
    public function it_deletes_all_messages()
    {
        $this->httpClient->expects($this->once())->method('request')->with('DELETE', 'v1/messages');

        $this->SUT->deleteAll();
    }

    /** @test */
    public function it_fetches_all_messages()
    {
        $responseData = [
            'items' => [
                $this->loadJsonFixture('plain'),
            ],
        ];
        $responseBody = json_encode($responseData);
        $this->httpClient
            ->expects($this->once())->method('request')->with('GET', 'v2/messages')
            ->willReturn(new Response(200, [], $responseBody));

        $actual = $this->SUT->fetchAll();

        $this->assertCount(1, $actual);
        $this->assertInstanceOf(Message::class, $actual[0]);
    }
}

<?php

namespace Prezent\InkBundle\Tests\Functional;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Sander Marechal
 */
class EmailTest extends WebTestCase
{
    public function testPreview()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/preview');

        // Template is parsed
        $this->assertCount(1, $crawler->filter('h1:contains("Hello world")'));

        // Inky is expanded
        $this->assertCount(1, $crawler->filter('table.container table.callout'));

        // CSS is inlined
        $this->assertCount(1, $crawler->filter('body[style*="background-color"]'));
    }

    public function testEmail()
    {
        $client = $this->createClient();
        $client->enableProfiler();

        $crawler = $client->request('GET', '/send');
        $collector = $client->getProfile()->getCollector('swiftmailer');

        $this->assertEquals(1, $collector->getMessageCount());

        $message = $collector->getMessages()[0];

        $this->assertInstanceOf(\Swift_Message::class, $message);
        $this->assertEquals('Hello world', $message->getSubject());
        $this->assertStringContainsString('Hello world', $message->getBody());

        $crawler = new Crawler($message->getChildren()[0]->getBody());

        // Template is parsed
        $this->assertCount(1, $crawler->filter('h1:contains("Hello world")'));

        // Inky is expanded
        $this->assertCount(1, $crawler->filter('table.container table.callout'));

        // CSS is inlined
        $this->assertCount(1, $crawler->filter('body[style*="background-color"]'));
    }
}

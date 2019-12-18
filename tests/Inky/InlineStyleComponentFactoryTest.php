<?php

namespace Prezent\InkBundle\Tests\Inky;

use PHPUnit\Framework\TestCase;
use Prezent\InkBundle\Inky\InlineStyleComponentFactory;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Sander Marechal
 */
class InlineStyleComponentFactoryTest extends TestCase
{
    public function testAbsoluteFile()
    {
        $kernel = $this->createMock(Kernel::class);
        $crawler = new Crawler('<link rel="stylesheet" href="' . __DIR__ . '/../Fixture/css/email.css" />');

        $inliner = new InlineStyleComponentFactory($kernel);

        foreach ($crawler->filter('link') as $node) {
            $inliner->parse($node);
        }

        $this->assertCount(1, $crawler->filter('style'));
        $this->assertStringContainsString('background-color', $crawler->filter('style')->text());
    }

    public function testPublicFile()
    {
        $kernel = $this->createMock(Kernel::class);
        $crawler = new Crawler('<link rel="stylesheet" href="css/email.css" />');

        $inliner = new InlineStyleComponentFactory($kernel);
        $inliner->setPublicDir(__DIR__ . '/../Fixture');

        foreach ($crawler->filter('link') as $node) {
            $inliner->parse($node);
        }

        $this->assertCount(1, $crawler->filter('style'));
        $this->assertStringContainsString('background-color', $crawler->filter('style')->text());
    }

    public function testRelativeFile()
    {
        $kernel = $this->createMock(Kernel::class);
        $kernel->method('getProjectDir')->willReturn(__DIR__ . '/../Fixture');

        $crawler = new Crawler('<link rel="stylesheet" href="css/email.css" />');
        $inliner = new InlineStyleComponentFactory($kernel);

        foreach ($crawler->filter('link') as $node) {
            $inliner->parse($node);
        }

        $this->assertCount(1, $crawler->filter('style'));
        $this->assertStringContainsString('background-color', $crawler->filter('style')->text());
    }

    public function testResource()
    {
        $kernel = $this->createMock(Kernel::class);
        $kernel->method('locateResource')->willReturn(__DIR__ . '/../Fixture/css/email.css');

        $crawler = new Crawler('<link rel="stylesheet" href="@App/css/email.css" />');
        $inliner = new InlineStyleComponentFactory($kernel);

        foreach ($crawler->filter('link') as $node) {
            $inliner->parse($node);
        }

        $this->assertCount(1, $crawler->filter('style'));
        $this->assertStringContainsString('background-color', $crawler->filter('style')->text());
    }

    public function testFileNotFound()
    {
        $kernel = $this->createMock(Kernel::class);
        $kernel->method('getProjectDir')->willReturn(__DIR__ . '/../Fixture');

        $crawler = new Crawler('<link rel="stylesheet" href="css/not-found.css" />');
        $inliner = new InlineStyleComponentFactory($kernel);

        $this->expectException(\RuntimeException::class);

        foreach ($crawler->filter('link') as $node) {
            $inliner->parse($node);
        }
    }
}

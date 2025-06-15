<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/MeowFactsClient.php';

use PHPUnit\Framework\TestCase;
use MeowFacts\MeowFactsClient;

final class MeowFactsClientTest extends TestCase
{
    private MeowFactsClient $client;

    protected function setUp(): void
    {
        $this->client = new MeowFactsClient();
    }

    public function testGetFactsReturnsArray(): void
    {
        $facts = $this->client->getFacts(1);
        $this->assertIsArray($facts);
        $this->assertNotEmpty($facts);
    }

    public function testGetFactsWithValidLang(): void
    {
        $facts = $this->client->getFacts(1, 'eng');
        $this->assertIsArray($facts);
    }

    public function testGetFactsWithUnsupportedLangThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getFacts(1, 'xyz');
    }

    public function testGetFactsWithInvalidCountTooLow(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getFacts(0);
    }

    public function testGetFactsWithInvalidCountTooHigh(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->client->getFacts(101);
    }

    public function testGetFactsWithId(): void
    {
        $facts = $this->client->getFacts(1, 'eng', '3');
        $this->assertIsArray($facts);
    }
}

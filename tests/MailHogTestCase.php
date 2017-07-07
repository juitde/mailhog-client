<?php

declare(strict_types=1);

namespace JUIT\Tests\MailHog;

use PHPUnit\Framework\TestCase;

class MailHogTestCase extends TestCase
{
    protected function getFixturesPath(): string
    {
        return __DIR__ . '/fixtures';
    }

    protected function loadFixture(string $type): array
    {
        return json_decode(file_get_contents($this->getFixturesPath() . '/' . $type . '.json'), true);
    }
}

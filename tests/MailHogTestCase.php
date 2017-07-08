<?php

declare(strict_types=1);

namespace JUIT\Tests\MailHog;

use PHPUnit\Framework\TestCase;

class MailHogTestCase extends TestCase
{
    protected function getFixturesPath(string $filename): string
    {
        return __DIR__ . '/fixtures/' . $filename;
    }

    protected function loadJsonFixture(string $type): array
    {
        $filename = $type . '.json';

        return json_decode(file_get_contents($this->getFixturesPath($filename)), true);
    }
}

<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Partials;

trait CreateSlugTrait
{
    private function createSlug(string $text): string
    {
        return strtolower(str_replace(' ', '-', $text));
    }
}

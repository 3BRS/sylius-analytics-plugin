<?php

namespace Tests\ThreeBRS\SyliusAnalyticsPlugin\Unit\Service;

use PHPUnit\Framework\TestCase;
use ThreeBRS\SyliusAnalyticsPlugin\Service\RequestLogSlugParser;

class RequestLogSlugParserTest extends TestCase
{
    public function testParseSlug()
    {
        $parser = new RequestLogSlugParser();
        self::assertSame(
            'bold-beach-cap',
            $parser->parseSlug('http://localhost:8080/en_US/products/bold-beach-cap?variant=139&what=ever#fragment'),
        );
    }
}

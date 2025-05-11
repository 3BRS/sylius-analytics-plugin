<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Webmozart\Assert\Assert;

final class RequestLogContext implements Context
{
    public function __construct(
        private SymfonyPageInterface $requestLogIndexPage
    ) {}

    /**
     * @When I go to the request logs page
     */
    public function iGoToRequestLogsPage(): void
    {
        $this->requestLogIndexPage->open();
    }

    /**
     * @Then I should see visit logs for all pages
     */
}

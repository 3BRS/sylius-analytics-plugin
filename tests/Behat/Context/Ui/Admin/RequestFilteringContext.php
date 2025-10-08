<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Session;
use Sylius\Behat\Service\Setter\ChannelContextSetterInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

final readonly class RequestFilteringContext implements Context
{
    public function __construct(
        private Session $session,
        private RouterInterface $router,
        private ClockInterface $clock,
        private ChannelContextSetterInterface $channelContextSetter,
    ) {
    }

    /**
     * @Given there are requests from :channel channel
     */
    public function thereAreRequestsFromChannel(ChannelInterface $channel): void
    {
        // Set the channel context so Symfony knows which channel to use
        $this->channelContextSetter->setChannel($channel);

        $locale = $channel->getDefaultLocale()->getCode();

        // Generate requests for the specific channel by visiting pages with channel context

        $url = $this->router->generate('sylius_shop_homepage', ['_locale' => $locale]);
        $this->session->visit($url);

        Assert::same(200, $this->session->getStatusCode(), 'Failed to load homepage');
    }

    /**
     * @Given there are requests to different routes for :channel channel
     */
    public function thereAreRequestsToDifferentRoutes(ChannelInterface $channel): void
    {
        // Set the channel context so Symfony knows which channel to use
        $this->channelContextSetter->setChannel($channel);

        $locale = $channel->getDefaultLocale()->getCode();

        // Visit different pages to create requests with different routes
        $homepageUrl = $this->router->generate('sylius_shop_homepage', ['_locale' => $locale]);
        $this->session->visit($homepageUrl);
        Assert::same(200, $this->session->getStatusCode(), 'Failed to load homepage');

        // Visit cart page
        $cartUrl = $this->router->generate('sylius_shop_cart_summary', ['_locale' => $locale]);
        $this->session->visit($cartUrl);
        Assert::same(200, $this->session->getStatusCode(), 'Failed to load cart page');
    }

    /**
     * @Given there are requests from different dates for :channel channel
     */
    public function thereAreRequestsFromDifferentDates(ChannelInterface $channel): void
    {
        // Set the channel context so Symfony knows which channel to use
        $this->channelContextSetter->setChannel($channel);

        $locale = $channel->getDefaultLocale()->getCode();

        // Generate some current requests
        $url = $this->router->generate('sylius_shop_homepage', ['_locale' => $locale]);
        $this->session->visit($url);

        // Visit cart page to generate another request
        $cartUrl = $this->router->generate('sylius_shop_cart_summary', ['_locale' => $locale]);
        $this->session->visit($cartUrl);
    }

    /**
     * @When I go to the request logs page
     */
    public function iGoToTheRequestLogsPage(): void
    {
        $url = $this->router->generate('threebrs_admin_statistics_plugin.request_log_index');
        $this->session->visit($url);
        Assert::same(200, $this->session->getStatusCode());
    }

    /**
     * @When I filter requests by channel :channel
     */
    public function iFilterRequestsByChannel(ChannelInterface $channel): void
    {
        $page = $this->session->getPage();

        // Look for channel filter and apply it
        $channelFilter = $page->find('css', 'input[name*="channel"]');
        Assert::notNull(
            $channelFilter,
            sprintf(
                'Channel filter input not found on the page %s',
                $this->session->getCurrentUrl(),
            ),
        );
        $channelFilter->setValue($channel->getCode());

        // Submit the filter form
        $filterButton = $page->find('css', 'button[type="submit"][data-test-filter]');
        Assert::notNull(
            $filterButton,
            sprintf(
                'Filter submit button not found on the page %s',
                $this->session->getCurrentUrl(),
            ),
        );
        $filterButton->submit();
    }

    /**
     * @When I filter requests by route name :routeName
     */
    public function iFilterRequestsByRouteName(string $routeName): void
    {
        $page = $this->session->getPage();

        // Look for route name filter and apply it
        $routeFilter = $page->find('css', 'input[name*="routeName"]');
        Assert::notNull(
            $routeFilter,
            sprintf(
                'Route name filter input not found on the page %s',
                $this->session->getCurrentUrl(),
            ),
        );
        $routeFilter->setValue($routeName);

        // Submit the filter form
        $filterButton = $page->find('css', 'button[type="submit"][data-test-filter]');
        Assert::notNull(
            $filterButton,
            sprintf(
                'Filter submit button not found on the page %s',
                $this->session->getCurrentUrl(),
            ),
        );
        $filterButton->submit();
    }

    /**
     * @When I filter requests by today's date
     */
    public function iFilterRequestsByTodaysDate(): void
    {
        Assert::same(200, $this->session->getStatusCode());

        $page = $this->session->getPage();
        $todayDate = $this->clock->now()->format('Y-m-d');

        // Look for date range filter inputs (from and to)
        $dateFromFilter = $page->find('css', 'input[name="criteria[createdAt][from][date]"]');
        Assert::notNull(
            $dateFromFilter,
            sprintf(
                'Date from filter input not found on the page %s',
                $this->session->getCurrentUrl(),
            ),
        );

        $dateToFilter = $page->find('css', 'input[name="criteria[createdAt][to][date]"]');
        Assert::notNull(
            $dateToFilter,
            sprintf(
                'Date to filter input not found on the page %s',
                $this->session->getCurrentUrl(),
            ),
        );

        // Set both from and to date to today's date
        $dateFromFilter->setValue($todayDate);
        $dateToFilter->setValue($todayDate);

        // Submit the filter form
        $filterButton = $page->find('css', 'button[type="submit"][data-test-filter]');
        Assert::notNull(
            $filterButton,
            sprintf(
                'Filter submit button not found on the page %s',
                $this->session->getCurrentUrl(),
            ),
        );
        $filterButton->submit();
    }

    /**
     * @Then I should see only requests from :channel channel
     */
    public function iShouldSeeOnlyRequestsFromChannel(ChannelInterface $channel): void
    {
        Assert::same(200, $this->session->getStatusCode());

        $page = $this->session->getPage();
        self::assertSomeResultsFound($page);

        $tbody = $page->find('css', 'tbody[data-test-grid-table-body]');

        Assert::notNull($tbody, 'Grid table body with data-test-grid-table-body not found');

        $rows = $tbody->findAll('css', 'tr');
        Assert::greaterThan(count($rows), 0, 'No filtered rows found');

        $channelCode = $channel->getCode();

        // Check that all visible rows contain the expected channel
        foreach ($rows as $row) {
            $rowText = $row->getText();
            Assert::contains($rowText, $channelCode, sprintf('Row should contain channel "%s"', $channelCode));
        }
    }

    /**
     * @Then I should not see requests from :channelName channel
     */
    public function iShouldNotSeeRequestsFromChannel(string $channelName): void
    {
        Assert::same(200, $this->session->getStatusCode());

        $page = $this->session->getPage();
        self::assertSomeResultsFound($page);

        $tbody = $page->find('css', 'tbody[data-test-grid-table-body]');

        Assert::notNull($tbody, 'Grid table body with data-test-grid-table-body not found');

        $rows = $tbody->findAll('css', 'tr');

        // Check that none of the visible rows contain the excluded channel
        foreach ($rows as $row) {
            $rowText = $row->getText();
            Assert::false(
                str_contains($rowText, $channelName),
                sprintf('Row should not contain channel "%s" but found it', $channelName),
            );
        }
    }

    /**
     * @Then I should see only cart summary requests
     */
    public function iShouldSeeOnlyCartSummaryPageRequests(): void
    {
        Assert::same(200, $this->session->getStatusCode());

        $page = $this->session->getPage();
        self::assertSomeResultsFound($page);

        $tbody = $page->find('css', 'tbody[data-test-grid-table-body]');

        Assert::notNull($tbody, 'Grid table body with data-test-grid-table-body not found');

        $rows = $tbody->findAll('css', 'tr');
        Assert::greaterThan(count($rows), 0, 'No filtered rows found');

        // Check that all visible rows contain product route
        foreach ($rows as $row) {
            $rowText = $row->getText();
            Assert::contains(
                $rowText,
                'sylius_shop_cart_summary',
                'Row should contain product route sylius_shop_cart_summary',
            );
        }
    }

    /**
     * @Then I should see only requests from today
     */
    public function iShouldSeeOnlyRequestsFromToday(): void
    {
        Assert::same(200, $this->session->getStatusCode());

        $page = $this->session->getPage();

        self::assertSomeResultsFound($page);

        $tbody = $page->find('css', 'tbody[data-test-grid-table-body]');

        Assert::notNull($tbody, 'Grid table body with data-test-grid-table-body not found');

        $rows = $tbody->findAll('css', 'tr');
        Assert::greaterThan(count($rows), 0, 'No filtered rows found for today');

        // Verify that today's date appears in the results
        $todayDate = $this->clock->now()->format('Y-m-d');

        foreach ($rows as $row) {
            $rowText = $row->getText();
            Assert::contains(
                $rowText,
                $todayDate,
                sprintf(
                    "Row should contain today's date (%s): %s",
                    $todayDate,
                    $rowText,
                ),
            );
        }
    }

    private static function assertSomeResultsFound(DocumentElement $page): void
    {
        Assert::notContains(
            $page->getContent(),
            'No results found',
            'No results found',
        );

        Assert::notNull(
            $page->find('css', 'tbody[data-test-grid-table-body]'),
            'Grid table body with data-test-grid-table-body not found',
        );
    }
}

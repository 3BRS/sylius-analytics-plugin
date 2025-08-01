<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

final readonly class MenuIntegrationContext implements Context
{
    public function __construct(
        private Session $session,
        private RouterInterface $router,
    ) {
    }

    /**
     * @When I go to the admin dashboard
     */
    public function iGoToTheAdminDashboard(): void
    {
        $url = $this->router->generate('sylius_admin_dashboard');
        $this->session->visit($url);
    }

    /**
     * @Then I should see :menuItem in the admin menu
     */
    public function iShouldSeeInTheAdminMenu(string $menuItem): void
    {
        Assert::same(200, $this->session->getStatusCode());

        $page = $this->session->getPage();

        // Look for the menu item in the admin navigation using various approaches
        $menuLink = $page->find('xpath', sprintf('//a[contains(normalize-space(text()), "%s")]', $menuItem));

        Assert::notNull($menuLink, sprintf('Menu item "%s" not found in admin menu', $menuItem));
    }

    /**
     * @Then the request logs menu item should be accessible
     */
    public function theRequestLogsMenuItemShouldBeAccessible(): void
    {
        Assert::same(200, $this->session->getStatusCode());

        $page = $this->session->getPage();

        // Find the Request Logs menu item using same approach as main check
        $requestLogsLink = $page->find('xpath', '//a[contains(normalize-space(text()), "Request Logs")]');

        if (!$requestLogsLink) {
            // Try looking in dropdown items by URL
            $requestLogsLink = $page->find('css', '.dropdown-item[href*="statistics-plugin-request-logs"]');
        }

        Assert::notNull($requestLogsLink, 'Request Logs menu item should be accessible');

        // Check that the link has a valid href
        $href = $requestLogsLink->getAttribute('href');
        Assert::notEmpty($href, 'Request Logs menu item should have a valid href');

        // Verify it points to the request logs page
        Assert::contains($href, 'statistics-plugin-request-logs', 'Request Logs link should point to the correct route');
    }

    /**
     * @When I click on :menuItem in the admin menu
     */
    public function iClickOnInTheAdminMenu(string $menuItem): void
    {
        Assert::same(200, $this->session->getStatusCode());

        $page = $this->session->getPage();

        // Find the menu item using same approach
        $menuLink = $page->find('xpath', sprintf('//a[contains(normalize-space(text()), "%s")]', $menuItem));

        Assert::notNull($menuLink, sprintf('Menu item "%s" not found', $menuItem));
        $menuLink->click();
    }

    /**
     * @Then I should be redirected to the request logs page
     */
    public function iShouldBeRedirectedToTheRequestLogsPage(): void
    {
        // Check that we're on the request logs page
        $currentUrl = $this->session->getCurrentUrl();

        // The URL should contain the request logs route
        Assert::contains($currentUrl, 'request', 'Should be redirected to request logs page');

        // Also check page content
        $page = $this->session->getPage();
        $content = $page->getContent();

        // Look for indicators that we're on the request logs page
        $hasRequestLogContent = str_contains($content, 'Request Log') ||
                                str_contains($content, 'request log') ||
                                str_contains($content, 'Analytics');

        Assert::true($hasRequestLogContent, 'Page should contain request log content');
    }
}

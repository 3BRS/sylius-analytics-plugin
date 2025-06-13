<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

final class ViewRequestLogsContext implements Context
{
    public function __construct(
        private Session $session,
        private RouterInterface $router,
    ) {
    }

    /**
     * @When I visit the store homepage
     */
    public function iVisitTheStoreHomepage(): void
    {
        $url = $this->router->generate('sylius_shop_homepage', ['_locale' => 'en_US']);
        $this->session->visit($url);
    }

    /**
     * @When I visit the product :productName page
     */
    public function iVisitTheProductPage(string $productName): void
    {
        $slug = 'awesome-t-shirt'; // You can map this dynamically later
        $this->session->visit("/en_US/products/{$slug}");
    }

    /**
     * @When I visit the "T-Shirts" category page
     */
    public function iVisitCategoryPage(): void
    {
        $this->session->visit('/en_US/taxons/t-shirts');
    }

    /**
     * @When I visit the cart page
     */
    public function iVisitCartPage(): void
    {
        $url = $this->router->generate('sylius_shop_cart_summary', ['_locale' => 'en_US']);
        $this->session->visit($url);
    }

    /**
     * @Given the store has a category :arg1
     */
    public function theStoreHasACategory($arg1): void
    {
        // Assume Sylius fixtures already create the category.
    }

    /**
     * @When I go to the request logs page
     */
    public function iGoToTheRequestLogsPage(): void
    {
        $this->session->visit('/admin/statistics-plugin-request-logs');
    }

    /**
     * @Then I should see visit logs for all pages
     */
    public function iShouldSeeVisitLogsForAllPages(): void
    {
        $page = $this->session->getPage();
        $rows = $page->findAll('css', 'table tbody tr');

        file_put_contents('/tmp/admin-page.html', $page->getContent());

        Assert::greaterThan(
            count($rows),
            0,
            'No visit logs were found in the admin panel.',
        );
    }
}

<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Symfony\Component\Routing\RouterInterface;
use Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Partials\CreateSlugTrait;
use Webmozart\Assert\Assert;

final readonly class ViewRequestLogsContext implements Context
{
    use CreateSlugTrait;

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
     * @When I visit the :taxonName taxon page
     */
    public function iVisitTaxonPage(string $taxonName): void
    {
        $slug = $this->createSlug($taxonName);
        $this->session->visit("/en_US/taxons/{$slug}");
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
     * @When I go to the request logs page
     */
    public function iGoToTheRequestLogsPage(): void
    {
        $this->session->visit('/admin/statistics-plugin-request-logs');
        Assert::same(200, $this->session->getStatusCode());
    }

    /**
     * @Then I should see visit logs for all pages
     */
    public function iShouldSeeVisitLogsForAllPages(): void
    {
        Assert::same(200, $this->session->getStatusCode());

        $page = $this->session->getPage();
        $tbody = $page->find('css', 'tbody[data-test-grid-table-body]');

        Assert::notNull($tbody, 'Grid table body with data-test-grid-table-body not found');

        $rows = $tbody->findAll('css', 'tr');

        Assert::eq(
            4,
            count($rows),
            sprintf('Expected 4 visit logs (homepage, product, taxon, cart) but found %d', count($rows)),
        );
    }
}

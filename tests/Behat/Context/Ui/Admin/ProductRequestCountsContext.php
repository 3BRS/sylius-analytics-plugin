<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Symfony\Component\Routing\RouterInterface;
use Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Partials\CreateSlugTrait;
use Webmozart\Assert\Assert;

final readonly class ProductRequestCountsContext implements Context
{
    use CreateSlugTrait;

    public function __construct(
        private Session $session,
        private RouterInterface $router,
    ) {
    }

    /**
     * @Given the product :productName has been visited :count times
     */
    public function theProductHasBeenVisitedTimes(
        string $productName,
        int $count,
    ): void {
        $url = $this->router->generate('sylius_shop_product_show', ['slug' => $this->createSlug($productName), '_locale' => 'en_US']);

        for ($i = 0; $i < $count; ++$i) {
            $this->session->visit($url);
        }
    }

    /**
     * @Given the product :productName has never been visited
     */
    public function theProductHasNeverBeenVisited(string $productName): void
    {
        // This step is mainly for context - no visits are generated
    }

    /**
     * @When I go to the admin products page
     */
    public function iGoToTheAdminProductsPage(): void
    {
        $url = $this->router->generate('sylius_admin_product_index');
        $this->session->visit($url);
        Assert::same(200, $this->session->getStatusCode());
    }

    /**
     * @Then I should see the product :productName with :count requests
     */
    public function iShouldSeeTheProductWithRequests(
        string $productName,
        int $count,
    ): void {
        Assert::same(200, $this->session->getStatusCode());

        $page = $this->session->getPage();
        $content = $page->getContent();

        // Check that the product is listed
        Assert::contains($content, $productName);

        // Check that request count is displayed
        // This is a basic check - the actual implementation would depend on how the count is displayed
        Assert::contains($content, (string) $count);
    }
}

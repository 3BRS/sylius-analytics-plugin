<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Partials\CreateSlugTrait;
use ThreeBRS\SyliusAnalyticsPlugin\Entity\RequestLog;
use Webmozart\Assert\Assert;

final class VisitLoggingContext implements Context
{
    use CreateSlugTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
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
     * @Then this request should be logged
     */
    public function thisRequestShouldBeLogged(): void
    {
        $logs = $this->entityManager
            ->getRepository(RequestLog::class)
            ->findAll();

        Assert::greaterThan(
            count($logs),
            0,
            'No requests were logged.',
        );
    }
}

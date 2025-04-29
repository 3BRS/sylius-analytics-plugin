<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;


final class VisitLoggingContext extends MinkContext implements Context
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RepositoryInterface $requestLogRepository,
        private ProductRepositoryInterface $productRepository,
        private TaxonRepositoryInterface $taxonRepository,
    ) {}

    /**
     * @Given I am on the homepage
     */
    public function iAmOnTheHomepage(): void
    {
        $this->getSession()->visit('/en_US');
    }

    /**
     * @Given I am on the product page :name
     */
    public function iAmOnTheProductPage(string $name): void
    {
        $product = $this->productRepository->findOneBy(['name' => $name]);
        Assert::notNull($product, sprintf('Product "%s" not found.', $name));

        $this->getSession()->visit('/en_US/products/' . $product->getSlug());
    }

    /**
     * @Given I am on the taxon page :name
     */
    public function iAmOnTheTaxonPage(string $name): void
    {
        $taxon = $this->taxonRepository->findOneBy(['name' => $name]);
        Assert::notNull($taxon, sprintf('Taxon "%s" not found.', $name));

        $this->getSession()->visit('/en_US/taxons/' . $taxon->getSlug());
    }

    /**
     * @Given I am on the cart summary page
     */
    public function iAmOnTheCartSummaryPage(): void
    {
        $this->getSession()->visit('/en_US/cart');
    }

    /**
     * @Then a visit log should exist with route :routeName
     */
    public function aVisitLogShouldExistWithRoute(string $routeName): void
    {
        $logs = $this->requestLogRepository->findBy(['routeName' => $routeName]);
        Assert::notEmpty($logs, sprintf('No visit log found with route "%s"', $routeName));
    }
}

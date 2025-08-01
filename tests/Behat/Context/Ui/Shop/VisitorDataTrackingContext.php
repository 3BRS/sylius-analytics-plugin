<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Partials\CreateSlugTrait;
use ThreeBRS\SyliusAnalyticsPlugin\Entity\RequestLogInterface;
use Webmozart\Assert\Assert;

final readonly class VisitorDataTrackingContext implements Context
{
    use CreateSlugTrait;

    public function __construct(
        private Session $session,
        private RouterInterface $router,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @When an anonymous user visits the store homepage
     */
    public function anAnonymousUserVisitsTheStoreHomepage(): void
    {
        $url = $this->router->generate('sylius_shop_homepage', ['_locale' => 'en_US']);
        $this->session->visit($url);
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
        $url = $this->router->generate('sylius_shop_product_show', ['slug' => $this->createSlug($productName), '_locale' => 'en_US']);
        $this->session->visit($url);
    }

    /**
     * @Then the request should be logged with IP address
     */
    public function theRequestShouldBeLoggedWithIpAddress(): void
    {
        // Check that a request log was created with IP address
        $requestLog = $this->getLatestRequestLog();
        Assert::notNull($requestLog->getIpAddress());
        Assert::notEmpty($requestLog->getIpAddress());
    }

    /**
     * @Then the request should be logged with user agent
     */
    public function theRequestShouldBeLoggedWithUserAgent(): void
    {
        // Check that a request log was created with user agent
        $requestLog = $this->getLatestRequestLog();
        Assert::notNull($requestLog->getUserAgent());
        Assert::notEmpty($requestLog->getUserAgent());
    }

    /**
     * @Then the request should be logged with no customer information
     */
    public function theRequestShouldBeLoggedWithNoCustomerInformation(): void
    {
        // Check that a request log was created without customer
        $requestLog = $this->getLatestRequestLog();
        Assert::null($requestLog->getCustomer());
    }

    /**
     * @Then the request should be logged with customer :customerEmail
     */
    public function theRequestShouldBeLoggedWithCustomer(string $customerEmail): void
    {
        // Check that a request log was created with the customer
        $requestLog = $this->getLatestRequestLog();
        Assert::notNull($requestLog->getCustomer());
        Assert::eq($requestLog->getCustomer()->getEmail(), $customerEmail);
    }

    /**
     * @Then the request should be logged with full URL containing product slug
     */
    public function theRequestShouldBeLoggedWithFullUrlContainingProductSlug(): void
    {
        // Check that a request log was created with full URL
        $requestLog = $this->getLatestRequestLog();
        Assert::notNull($requestLog->getUrl());
        Assert::contains($requestLog->getUrl(), 't-shirt');
    }

    /**
     * @Then the request should be logged with route name :routeName
     */
    public function theRequestShouldBeLoggedWithRouteName(string $routeName): void
    {
        // Check that a request log was created with the specified route name
        $requestLog = $this->getLatestRequestLog();
        Assert::eq($requestLog->getRouteName(), $routeName);
    }

    /**
     * @Then the request should be logged with channel information
     */
    public function theRequestShouldBeLoggedWithChannelInformation(): void
    {
        // Check that a request log was created with channel information
        $requestLog = $this->getLatestRequestLog();
        Assert::notNull($requestLog->getChannel());
    }

    private function getLatestRequestLog(): RequestLogInterface
    {
        $repository = $this->entityManager->getRepository(RequestLogInterface::class);
        $requestLogs = $repository->findBy([], ['createdAt' => 'DESC'], 1);

        Assert::notEmpty($requestLogs, 'No request log found');

        return $requestLogs[0];
    }
}

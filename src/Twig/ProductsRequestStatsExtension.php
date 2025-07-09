<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Twig;

use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use ThreeBRS\SyliusAnalyticsPlugin\Repository\RequestLogRepositoryInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ProductsRequestStatsExtension extends AbstractExtension
{
    /**
     * @param ProductRepositoryInterface<ProductInterface> $productRepository
     */
    public function __construct(
        private RequestLogRepositoryInterface $requestLogRepository,
        private ProductRepositoryInterface $productRepository,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('product_request_count', [$this, 'getRequestCount']),
        ];
    }

    public function getRequestCount(int $productId): int
    {
        $product = $this->productRepository->find($productId);

        if (!$product instanceof ProductInterface) {
            return 0;
        }

        $slug = $product->getTranslation()->getSlug();

        if ($slug === null) {
            return 0;
        }

        return $this->requestLogRepository->countShopProductRequests($slug);
    }
}

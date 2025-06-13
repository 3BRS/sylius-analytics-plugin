<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Controller\Admin;

use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ThreeBRS\SyliusAnalyticsPlugin\Repository\RequestLogRepositoryInterface;

final class ProductRequestStatsController extends AbstractController
{
    /**
     * @param ProductRepositoryInterface<ProductInterface> $productRepository
     */
    public function __construct(
        private RequestLogRepositoryInterface $requestLogRepository,
        private ProductRepositoryInterface $productRepository,
    ) {
    }

    #[Route('/admin/_widget/product/{id}/request-count', name: 'threebrs_admin_product_request_count', methods: ['GET'])]
    public function __invoke(int $id): Response
    {
        /** @var ProductInterface|null $product */
        $product = $this->productRepository->find($id);

        if (null === $product) {
            throw $this->createNotFoundException('Product not found.');
        }

        $slug = $product->getTranslation()->getSlug();

        if ($slug === null) {
            throw new \RuntimeException('Product slug is null.');
        }

        $count = $this->requestLogRepository->countShopProductRequests($slug);

        return $this->render('@ThreeBRSSyliusAnalyticsPlugin/admin/shared/product/show/_request_count.html.twig', [
            'requestCount' => $count,
        ]);
    }
}

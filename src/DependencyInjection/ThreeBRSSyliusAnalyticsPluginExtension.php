<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use ThreeBRS\SyliusAnalyticsPlugin\Entity\RequestLog;
use ThreeBRS\SyliusAnalyticsPlugin\Entity\RequestLogInterface;
use ThreeBRS\SyliusAnalyticsPlugin\Repository\RequestLogRepository;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\GridBundle\DependencyInjection\Compiler\RegisterGridsPass;
use Sylius\Bundle\GridBundle\SyliusGridBundle;


final class ThreeBRSSyliusAnalyticsPluginExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        /* $this->registerResources(
            'threebrs.statistics_plugin',    
            'doctrine/orm',                    
            [
                'request_log' => [
                    'classes' => [
                        'model' => RequestLog::class,
                        'interface' => RequestLogInterface::class,
                        'repository' => RequestLogRepository::class,
                        'controller' => ResourceController::class, 


                    ],
                ],
            ],
            $container
        ); */



        $yamlLoader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $yamlLoader->load('services.yaml');
 
        
        /* $container->setParameter('sylius_grid.grids_paths', [
            realpath(__DIR__.'/../../config/packages') => '/admin',
        ]); */

    
    }

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependDoctrineMigrations($container);
    }

    protected function getMigrationsNamespace(): string
    {
        return 'ThreeBRS\\SyliusAnalyticsPlugin\\Migrations';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@ThreeBRSSyliusAnalyticsPlugin/Migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return [
            'Sylius\Bundle\CoreBundle\Migrations',
        ];
    }
}

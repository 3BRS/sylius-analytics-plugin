<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use ThreeBRS\SyliusAnalyticsPlugin\Entity\VisitLog;


final class ThreeBRSSyliusAnalyticsPluginExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->registerResources(
            'sylius_analytics',     
            'doctrine/orm',           
            [
                'visit_log' => [
                    'classes' => [
                        'model' => VisitLog::class,
                    ],
                ],
            ],                     
            $container                
        );
        
        
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.xml');
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

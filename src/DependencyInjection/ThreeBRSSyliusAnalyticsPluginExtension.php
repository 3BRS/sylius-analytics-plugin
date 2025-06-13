<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class ThreeBRSSyliusAnalyticsPluginExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->setParameter('threebrs.test_flag', 'hello_from_plugin');

        $yamlLoader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $yamlLoader->load('services.yaml');
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

    /**
     * @return array<string>
     */
    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return [
            'Sylius\Bundle\CoreBundle\Migrations',
        ];
    }
}

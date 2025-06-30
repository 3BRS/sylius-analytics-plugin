<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ThreeBRSSyliusAnalyticsPluginExtension extends AbstractResourceExtension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $yamlLoader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $yamlLoader->load('services.yaml');
    }
}

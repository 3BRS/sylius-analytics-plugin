<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();

    $services->set('tests.threebrs.sylius_analytics_plugin.context.ui.shop.visit_logging', \Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Shop\VisitLoggingContext::class)
        ->public()
        ->args([
            service('doctrine.orm.entity_manager'),
            service('behat.mink.default_session'),
            service('router'),
        ]);

    $services->set('tests.threebrs.sylius_analytics_plugin.context.ui.admin.view_request_logs', \Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Admin\ViewRequestLogsContext::class)
        ->public()
        ->args([
            service('behat.mink.default_session'),
            service('router'),
        ]);

    $services->set('tests.threebrs.sylius_analytics_plugin.context.ui.admin.dashboard_statistics', \Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Admin\DashboardStatisticsContext::class)
        ->public()
        ->args([
            service('behat.mink.default_session'),
            service('router'),
        ]);

    $services->set('tests.threebrs.sylius_analytics_plugin.context.ui.admin.product_request_counts', \Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Admin\ProductRequestCountsContext::class)
        ->public()
        ->args([
            service('behat.mink.default_session'),
            service('router'),
        ]);

    $services->set('tests.threebrs.sylius_analytics_plugin.context.ui.admin.request_filtering', \Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Admin\RequestFilteringContext::class)
        ->public()
        ->args([
            service('behat.mink.default_session'),
            service('router'),
            service('clock'),
            service('sylius.behat.channel_context_setter'),
        ]);

    $services->set('tests.threebrs.sylius_analytics_plugin.context.ui.shop.visitor_data_tracking', \Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Shop\VisitorDataTrackingContext::class)
        ->public()
        ->args([
            service('behat.mink.default_session'),
            service('router'),
            service('doctrine.orm.entity_manager'),
            service('sylius.repository.customer'),
        ]);

    $services->set('tests.threebrs.sylius_analytics_plugin.context.cli.console_command', \Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Cli\ConsoleCommandContext::class)
        ->public()
        ->args([
            service('kernel'),
            service('doctrine.orm.entity_manager'),
            service('sylius.repository.channel'),
        ]);

    $services->set('tests.threebrs.sylius_analytics_plugin.context.ui.admin.menu_integration', \Tests\ThreeBRS\SyliusAnalyticsPlugin\Behat\Context\Ui\Admin\MenuIntegrationContext::class)
        ->public()
        ->args([
            service('behat.mink.default_session'),
            service('router'),
        ]);
};

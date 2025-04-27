<p align="center">
    <a href="https://www.3brs.com" target="_blank">
        <img src="https://3brs1.fra1.cdn.digitaloceanspaces.com/3brs/logo/3BRS-logo-sylius-200.png"/>
    </a>
</p>

<h1 align="center">
Sylius Analytics Plugin
<br />
	<a href="https://packagist.org/packages/3brs/sylius-analytics-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/3brs/sylius-analytics-plugin" />
    </a>
    <a href="https://packagist.org/packages/3brs/sylius-analytics-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/3brs/sylius-analytics-plugin" />
    </a>
    <a href="https://circleci.com/gh/3BRS/sylius-analytics-plugin" title="Build status" target="_blank">
        <img src="https://circleci.com/gh/3BRS/sylius-analytics-plugin.svg?style=shield" />
    </a>
</h1>

## Features

* Asynchronously logs visits to all frontend pages (excluding admin)
* Tracks visitor data: channel, full URL, route name, customer, session ID, IP address, user agent, and timestamp
* Displays:
  - Most visited pages in the last 7 days
  - Visit counts on product detail pages
  - Full log of visits with filtering capabilities inside the admin panel
* Built on top of Symfony Messenger for asynchronous processing

<p align="center">
	<img src="https://raw.githubusercontent.com/3BRS/sylius-analytics-plugin/master/docs/admin_logs_list.png"/>
</p>

<p align="center">
	<img src="https://raw.githubusercontent.com/3BRS/sylius-analytics-plugin/master/docs/product_visit_count.png"/>
</p>

## Installation

1. Require the plugin via Composer:
```bash
composer require 3brs/sylius-analytics-plugin


If using locally (path repository), add this to your composer.json:

{
  "repositories": [
    {
      "type": "path",
      "url": "../path-to/3brs/sylius-analytics-plugin"
    }
  ]
}

Then run:

composer update 3brs/sylius-analytics-plugin


Register the plugin in config/bundles.php:

ThreeBRS\SyliusAnalyticsPlugin\ThreeBRSSyliusAnalyticsPlugin::class => ['all' => true],


Import the routing inside config/routes.yaml:

threebrs_sylius_analytics_plugin_admin:
    resource: "@ThreeBRSSyliusAnalyticsPlugin/config/admin_routing.yaml"
    prefix: /admin

threebrs_sylius_analytics_plugin_shop:
    resource: "@ThreeBRSSyliusAnalyticsPlugin/config/shop_routing.yaml"
    prefix: /


Configure Sylius Resource in config/packages/sylius_resource.yaml:

sylius_resource:
    resources:
        threebrs.statistics_plugin.request_log:
            classes:
                model: ThreeBRS\SyliusAnalyticsPlugin\Entity\RequestLog
                interface: ThreeBRS\SyliusAnalyticsPlugin\Entity\RequestLogInterface
                repository: ThreeBRS\SyliusAnalyticsPlugin\Repository\RequestLogRepository

Configure Doctrine mapping (if needed):

doctrine:
    orm:
        mappings:
            ThreeBRSSyliusAnalyticsPlugin:
                type: attribute
                dir: '%kernel.project_dir%/vendor/3brs/sylius-analytics-plugin/src/Entity'
                prefix: 'ThreeBRS\SyliusAnalyticsPlugin\Entity'
                is_bundle: false

Configure Symfony Messenger routing in config/packages/messenger.yaml:

framework:
    messenger:
        transports:
            async: '%env(MESSENGER_TRANSPORT_DSN)%'
        routing:
            'ThreeBRS\SyliusAnalyticsPlugin\Message\LogVisitMessage': async

Usage
The plugin will automatically log visits on:

Homepage

Product detail pages

Category pages

Cart pages

Other shop pages

You can view the statistics inside the admin panel under the Analytics section.
Visit logs are processed asynchronously using Symfony Messenger.

Development
Setup
Develop your plugin code inside /src

Useful commands are available inside /bin

Testing
After making changes, ensure that tests pass:


composer install
bin/phpstan.sh
bin/ecs.sh
vendor/bin/behat
Testing Application Setup
Inside tests/Application/:


composer install
bin/console doctrine:database:create --env=test
bin/console doctrine:migrations:migrate --env=test
yarn install
yarn build
symfony serve -d
vendor/bin/behat
License
This library is under the MIT license.

Credits
Developed by 3BRS







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
  <img src="https://github.com/3BRS/sylius-analytics-plugin/blob/AK/doc/admin-dashboard-request-logs.png?raw=true" />
</p>


## Installation

1. Run `composer require 3brs/sylius-analytics-plugin`.
2. Register `ThreeBRS\SyliusShipmentExportPlugin\ThreeBRSSyliusShipmentExportPlugin::class => ['all' => true]` in your `config/bundles.php`.
3. Import `@ThreeBRSSyliusAnalyticsPlugin/Resources/config/routing.yml` in your `config/routes.yaml`


```
Import the routing inside config/routes.yaml:

threebrs_sylius_analytics_plugin_admin:
    resource: "@ThreeBRSSyliusAnalyticsPlugin/config/admin_routing.yaml"
    prefix: /admin
```



Usage
The plugin will automatically log visits on:

Homepage

Product detail pages

Category pages

Cart pages

Other shop pages

You can view the statistics inside the admin panel under the Analytics section.
Visit logs are processed asynchronously using Symfony Messenger.



## Development

### Usage

- Develop your plugin in `/src`
- See `bin/` for useful commands

### Testing

After your changes you must ensure that the tests are still passing.

```bash
$ composer install
$ bin/phpstan.sh
$ bin/ecs.sh
```

License
-------
This library is under the MIT license.

Credits
-------
Developed by [3BRS](https://3brs.com)<br>

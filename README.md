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
  <img src="https://github.com/3BRS/sylius-analytics-plugin/blob/AK/doc/admin-dashboard-request-logs.png?raw=true" />
</p>


## Installation

1. Run `composer require 3brs/sylius-analytics-plugin`.
2. Register `ThreeBRS\SyliusAnalyticsPlugin\ThreeBRSSyliusAnalyticsPlugin::class => ['all' => true],` in your `config/bundles.php`.
3. Import the plugin's routing files in `config/routes.yaml`:

    ```yaml
    threebrs_statistics_plugin_admin_routing_file:
        resource: "@ThreeBRSSyliusAnalyticsPlugin/config/routes/admin_routing.yaml"
        prefix: '%sylius_admin.path_name%'
    ```
4. Messenger Configuration

    Default Behavior (Synchronous in Dev/Test)

    By default, the plugin uses a **synchronous transport** for `LogVisitMessage` in development and testing environments. This makes it easy to test and develop without running background workers.

    To enable sync mode, make sure the following is set in your `.env` or `.env.test`:

    ```dotenv
    THREEBRS_MESSENGER_TRANSPORT_LOG_VISIT_DSN=sync://
    ```

    To enable asynchronous logging in production or staging environments, configure the transport to use Doctrine:

    ```dotenv
    THREEBRS_MESSENGER_TRANSPORT_LOG_VISIT_DSN=doctrine://default
    ```

    This offloads request logging to a message queue, improving frontend performance.
    Then, make sure to run the Messenger worker:

    ```dotenv
    bin/console messenger:consume log_visit
    ```





5. To control how many past days are considered when calculating the "Most Requested Pages" in the admin dashboard, define the following parameter in your project config (Default: 7 days):

    ```
    # config/packages/_sylius.yaml (or your custom environment config)

    parameters:
        threebrs_analytics_plugin.request_log_days: 7 # Adjust this number as needed
    ```
6. Background worker running required for async logging using

    ```bash
    bin/console messenger:consume async -vv
    ```
7. Create and run doctrine database migrations

    ```bash
    bin/console doctrine:migrations:diff
    bin/console doctrine:migrations:migrate
    ```
    
> ⚠️ **Note:**  
> It is recommended to enable UTF-8 support in your Symfony routing configuration to ensure proper handling of non-ASCII characters in URLs (e.g., slugs with diacritics).
>
> Add the following to your `config/packages/routing.yaml`:
>
> ```yaml
> framework:
>     router:
>         utf8: true
> ```

## Usage

The plugin will automatically log requests on:

- Homepage
- Product detail pages
- Category pages
- Cart pages
- Other shop pages

You can view the statistics inside the admin panel under the Analytics section.  
All logs are processed asynchronously using Symfony Messenger.

## Development


### Usage

- Develop your plugin logic inside `/src`
- See `bin/` for useful dev tools

### Testing

After making changes, make sure tests and checks pass:

```bash
composer install
bin/phpstan.sh
bin/ecs.sh
```


License
-------
This library is under the MIT license.

Credits
-------
Developed by [3BRS](https://3brs.com)

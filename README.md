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
  - Most requested pages in the last X days (configurable)
  - Request counts on product detail pages
  - Full log of requests with filtering capabilities inside the admin panel
* Built on top of Symfony Messenger for asynchronous processing

<p align="center">
  <img src="https://github.com/3BRS/sylius-analytics-plugin/blob/AK/doc/admin-dashboard-request-logs.png?raw=true" />
</p>


## Installation

1. Run `composer require 3brs/sylius-analytics-plugin`.

2. Register plugin in your `config/bundles.php`
   ```php
   ThreeBRS\SyliusAnalyticsPlugin\ThreeBRSSyliusAnalyticsPlugin::class => ['all' => true],`
   ```

3. Import configuration to `config/packages/threebrs_sylius_analytics_plugin.yaml`:

    ```yaml
    imports:
        - { resource: "@ThreeBRSSyliusAnalyticsPlugin/config/config.yaml" }
    ```

4. Import routing to `config/routes.yaml`:

    ```yaml
    threebrs_statistics_plugin_routing_file:
        resource: "@ThreeBRSSyliusAnalyticsPlugin/config/routes.yaml"
        prefix: '%sylius_admin.path_name%'
    ```

5. ### Messenger Transport Configuration

    This plugin uses **Symfony Messenger** to log requests.

    #### Default Behavior (Synchronous)

    * By default, the plugin uses **synchronous processing** via the `sync://` DSN. This means that log messages are processed immediately, without requiring any queue or worker. This is ideal for development and testing environments.

    * To enable this mode, set the following in your `.env`:

    ```dotenv
    THREEBRS_MESSENGER_TRANSPORT_LOG_VISIT_DSN=sync://
    ```

    * For better performance in production or staging environments, you can configure the plugin to log requests asynchronously using a queue (e.g., Doctrine transport)

    ```dotenv
    THREEBRS_MESSENGER_TRANSPORT_LOG_VISIT_DSN=doctrine://default
    ```

    * After setting this, you must run the Messenger worker to process the queued log messages:

    ```bash
    bin/console messenger:consume log_visit -vv
    ```
    * Only use this mode if your project supports background workers and a transport like Doctrine, Redis, etc.

6. Configure how many past days are considered when calculating the **"Most Visited Pages"** in the admin dashboard by setting the following parameter:

    ```yaml
    parameters:
        threebrs_analytics_plugin.request_log_days: 7 # Change as needed
    ```

7. Generate and run Doctrine migrations:

    ```bash
    bin/console doctrine:migrations:diff 
    bin/console doctrine:migrations:migrate
    ```

8. Optional: Enable UTF-8 support in routing for better slug handling (diacritics, etc.):

    ```yaml
    # config/packages/routing.yaml
    framework:
        router:
            utf8: true
    ```

## Usage

The plugin will automatically log requests on:

- Homepage
- Product detail pages
- Category pages
- Cart pages
- Other shop pages

You can view the statistics inside the admin panel under the Analytics section.  
Log processing is handled via Symfony Messenger, either synchronously (by default) or asynchronously if a queue is configured.

## Development

### Setup

Initialize the development environment:

```bash
make init
```

This command installs dependencies, sets up the database, and prepares frontend assets (or follow related steps in Makefile).

### Usage

- Develop your plugin logic inside `/src`
- See `bin/` for useful dev tools

### Testing

Run all tests and quality checks:

```bash
make ci
```

Run individual checks:

```bash
make phpstan    # Static analysis
make ecs        # Code style check
make fix        # Fix code style issues
make lint       # Symfony and Doctrine linting
make behat      # Behavioral tests
```

### Database Management

```bash
make backend        # Set up database with migrations
make fixtures       # Load test fixtures
make recreate_db    # Recreate database from scratch
```

### Development Server

Start the development environment:

```bash
make run           # Start Docker containers
make bash          # Access PHP container shell
```

### Other Useful Commands

```bash
make static        # Run static analysis (PHPStan + ECS + Lint)
make cache         # Clear application cache
```

All commands use the test environment by default. See the Makefile for detailed implementation of each target.


License
-------
This library is under the MIT license.

Credits
-------
Developed by [3BRS](https://3brs.com)

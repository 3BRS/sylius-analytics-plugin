<p align="center">
    <a href="https://www.3brs.com" target="_blank">
        <img src="https://3brs1.fra1.cdn.digitaloceanspaces.com/3brs/logo/3BRS-logo-sylius-200.png" alt="3BRS Logo"/>
    </a>
</p>

<br>

<h1 align="center">
    Sylius Analytics Plugin
    <br />
    <a href="https://packagist.org/packages/3brs/sylius-analytics-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/3brs/sylius-analytics-plugin" alt="License"/>
    </a>
    <a href="https://packagist.org/packages/3brs/sylius-analytics-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/3brs/sylius-analytics-plugin" alt="Packagist Version"/>
    </a>
    <a href="https://circleci.com/gh/3BRS/sylius-analytics-plugin" title="Build status" target="_blank">
        <img src="https://circleci.com/gh/3BRS/sylius-analytics-plugin.svg?style=shield" alt="Build Status"/>
    </a>
</h1>

---

## Features

* **Asynchronous Visit Logging**: Logs visits to all frontend pages (excluding admin) without blocking user experience.
* **Comprehensive Visitor Data**: Tracks essential visitor information including channel, full URL, route name, customer, session ID, IP address, user agent, and timestamp.
* **Admin Dashboard Insights**:
    * Displays **most requested pages** within a configurable last X days.
    * Shows **request counts** directly on product detail pages.
    * Provides a **full log of requests** with powerful filtering capabilities.
* **Built with Symfony Messenger**: Leverages Symfony Messenger for efficient asynchronous processing of log data.

<p align="center">
  <img src="https://github.com/3BRS/sylius-analytics-plugin/blob/AK/doc/admin-dashboard-request-logs.png?raw=true" alt="Admin Dashboard Request Logs Screenshot" />
</p>

---

## Installation

Follow these steps to integrate the Sylius Analytics Plugin into your project:

1.  **Install via Composer**:
    ```bash
    composer require 3brs/sylius-analytics-plugin
    ```

2.  **Register the Plugin**:
    Add the plugin to your `config/bundles.php` file:
    ```php
    ThreeBRS\SyliusAnalyticsPlugin\ThreeBRSSyliusAnalyticsPlugin::class => ['all' => true],
    ```

3.  **Import Routing Files**:
    Include the plugin's routing in your `config/routes.yaml`:
    ```yaml
    threebrs_statistics_plugin_routing_file:
        resource: "@ThreeBRSSyliusAnalyticsPlugin/config/routes.yaml"
        prefix: '%sylius_admin.path_name%'
    ```

4.  ### Messenger Transport Configuration

    This plugin utilizes **Symfony Messenger** for logging requests. You can configure its transport based on your environment needs.

    #### Default Behavior (Synchronous)

    * By default, the plugin operates in **synchronous processing** mode using the `sync://` DSN. This means log messages are processed immediately without needing a queue or worker, which is perfect for **development and testing**.

    * To ensure this mode is active, set the following in your `.env` file:
        ```dotenv
        THREEBRS_MESSENGER_TRANSPORT_LOG_VISIT_DSN=sync://
        ```

    #### For Asynchronous Processing
    
    * For enhanced performance in **production or staging environments**, configure the plugin to log requests asynchronously using a message queue (e.g., Doctrine transport):
        ```dotenv
        THREEBRS_MESSENGER_TRANSPORT_LOG_VISIT_DSN=doctrine://default
        ```

    * After setting an asynchronous DSN, you **must run the Messenger worker** to process the queued log messages:
        ```bash
        bin/console messenger:consume log_visit -vv
        ```
    * **Note**: Only use asynchronous mode if your project supports background workers and a suitable transport like Doctrine, Redis, or RabbitMQ.

5.  **Configure "Most Visited Pages" Days**:
    Determine how many past days are considered for the **“Most Visited Pages”** calculation in the admin dashboard by setting this parameter:
    ```yaml
    parameters:
        threebrs_analytics_plugin.request_log_days: 7 # Adjust value as needed (e.g., 30 for last month)
    ```

6.  **Generate and Run Migrations**:
    Apply the necessary database changes:
    ```bash
    bin/console doctrine:migrations:diff
    bin/console doctrine:migrations:migrate
    ```

7.  **Optional: Enable UTF-8 Routing**:
    For better slug handling (e.g., diacritics in URLs), enable UTF-8 support in your routing configuration:
    ```yaml
    # config/packages/routing.yaml
    framework:
        router:
            utf8: true
    ```

---

## Usage

Once installed and configured, the plugin will automatically begin logging requests for the following shop pages:

* **Homepage**
* **Product detail pages**
* **Category pages**
* **Cart pages**
* **Other general shop pages**

You can access and review all collected statistics within the Sylius admin panel under the dedicated **Analytics** section. Remember, log processing is handled via Symfony Messenger, either synchronously (by default) or asynchronously if you've configured a queue.

---

## Development

### Getting Started

* All plugin logic is developed within the `/src` directory.
* Refer to the `bin/` directory for useful development tools and scripts.

### Testing

After making any changes, ensure all tests and code quality checks pass:

```bash
composer install
bin/phpstan.sh
bin/ecs.sh
<?php

declare(strict_types=1);

use Behat\Config\Config;

return (new Config())
    ->import([
        'suites/view_logged_requests.php',
        'suites/visit_logging.php',
        'suites/admin_dashboard_statistics.php',
        'suites/admin_product_request_counts.php',
        'suites/admin_request_filtering.php',
        'suites/visitor_data_tracking.php',
        'suites/console_command.php',
        'suites/admin_menu_integration.php',
    ]);

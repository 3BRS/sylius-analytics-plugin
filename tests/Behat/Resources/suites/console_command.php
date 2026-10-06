<?php

declare(strict_types=1);

use Behat\Config\Config;
use Behat\Config\Filter\TagFilter;
use Behat\Config\Profile;
use Behat\Config\Suite;

return (new Config())
    ->withProfile(
        (new Profile('default'))
            ->withSuite(
                (new Suite('console_command'))
                    ->withContexts(
                        'tests.threebrs.sylius_analytics_plugin.context.cli.console_command',
                        'sylius.behat.context.hook.doctrine_orm',
                        'sylius.behat.context.setup.channel',
                        'sylius.behat.context.setup.product',
                        'sylius.behat.context.setup.admin_security',
                        'sylius.behat.context.setup.customer',
                        'sylius.behat.context.setup.taxonomy',
                        'sylius.behat.context.setup.admin_user',
                    )
                    ->withFilter(new TagFilter('@console_command')),
            ),
    );

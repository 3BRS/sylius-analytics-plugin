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
                (new Suite('admin_menu_integration'))
                    ->withContexts(
                        'tests.threebrs.sylius_analytics_plugin.context.ui.admin.menu_integration',
                        'sylius.behat.context.hook.doctrine_orm',
                        'sylius.behat.context.setup.channel',
                        'sylius.behat.context.setup.product',
                        'sylius.behat.context.setup.admin_security',
                        'sylius.behat.context.setup.customer',
                        'sylius.behat.context.setup.taxonomy',
                        'sylius.behat.context.setup.admin_user',
                        'sylius.behat.context.ui.admin.login',
                    )
                    ->withFilter(new TagFilter('@admin_menu_integration')),
            ),
    );

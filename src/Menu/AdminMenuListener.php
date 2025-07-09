<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusAnalyticsPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class AdminMenuListener implements EventSubscriberInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.menu.admin.main' => 'addRequestLogsToCatalog',
        ];
    }

    public function addRequestLogsToCatalog(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $catalogSection = $menu->getChild('catalog');

        if ($catalogSection !== null) {
            $catalogSection
                ->addChild('threebrs_request_logs', [
                    'route' => 'threebrs_admin_statistics_plugin.request_log_index',
                ])
                ->setLabel($this->translator->trans('threebrs.ui.statistics_plugin.request_logs'));
        }
    }
}

<?php

namespace App\EventListener;

use Survos\BootstrapBundle\Event\KnpMenuEvent;
use Survos\BootstrapBundle\Service\MenuService;
use Survos\BootstrapBundle\Traits\KnpMenuHelperInterface;
use Survos\BootstrapBundle\Traits\KnpMenuHelperTrait;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[AsEventListener(event: KnpMenuEvent::PAGE_MENU, method: 'pageMenu')]
final class AppMenuEventListener implements KnpMenuHelperInterface
{
    use KnpMenuHelperTrait;

    // this should be optional, not sure we really need it here.
    public function __construct(
        protected MenuService                                $menuService, // helper for auth menus, etc.
        private ?AuthorizationCheckerInterface $security = null)
    {
    }

    #[AsEventListener(event: KnpMenuEvent::NAVBAR_MENU2)]
    public function topIconMenu(KnpMenuEvent $event): void
    {
        $menu = $event->getMenu();
        foreach ([
            'Source Code' => 'hugeicons:github',
            'Sponsor' => 'mdi:heart-outline'
                     ] as $label => $icon ) {
            $this->add($menu, label: $label, icon: $icon);
        }

    }

    #[AsEventListener(event: KnpMenuEvent::NAVBAR_MENU)]
    public function navbarMenu(KnpMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $options = $event->getOptions();

        $this->add($menu, 'app_homepage', icon: 'tabler:home');

        foreach (['ui-alerts'=>'tabler:alert-circle',
//                     'cards-basic',
                     'ui-badges'=>'tabler:badge',
                     'ui-accordion' => 'vaadin:accordion-menu'] as $pageCode=>$icon) {
            $this->add($menu, 'app_page', [
                'code' => $pageCode],
                label: $pageCode,
                icon: $icon);
        }

//        $this->add($menu, 'app_homepage');
        // for nested menus, don't add a route, just a label, then use it for the argument to addMenuItem

        $nestedMenu = $this->addSubmenu($menu, 'Flashes', icon: 'tabler:columns', extras: [
            'maxItemsPerColumn' => 8
        ]);

        for ($x = 1; $x <= 12; $x++) {
            // $this->addMenuItem($nestedMenu, ['route' => 'survos_base_credits', 'rp' => ['type' => $type], 'label' => ucfirst($type)]);
            $flashType = ['success','warning','error'][$x % 3];
            $rp = [
                'flashType' => $flashType,
                'msg' => "$flashType $x"
            ];
            $this->add($nestedMenu, route: 'app_flash', rp: $rp, label: "$flashType $x",

                badge: (($x % 5) == 0) ? '5!' : '') ;
        }
    }

    #[AsEventListener(event: KnpMenuEvent::FOOTER_MENU)]
    public function footerMenu(KnpMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $options = $event->getOptions();
        $subMenu = $this->addSubmenu($menu, 'github');
        $this->add($subMenu, uri: 'https://github.com');
    }

    #[AsEventListener(event: KnpMenuEvent::SEARCH_MENU)]
    #[AsEventListener(event: KnpMenuEvent::NAVBAR_MENU)]
    public function searchMenu(KnpMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $options = $event->getOptions();
        $subMenu = $this->addSubmenu($menu, 'multi-dropdown search!', icon: 'tabler:search');
        foreach ([
                     [
                         'icon' => "tabler:search",
                         'route' => 'app_search',
                         'rp' => [
                             'table' => 'owner'
                         ],
                         'label' => "Museums",
                     ],
                     [
                         'icon' => "tabler:database",
                         'route' => 'app_search',
                         'rp' => [
                             'table' => 'pixies'
                         ],
                         'label' => "Search Pixies",
                     ],

                 ] as $m) {
            $this->add($subMenu, $m['route'], $m['rp'], $m['label'], icon: $m['icon']);

        }
    }

    public function pageMenu(KnpMenuEvent $event): void
    {
    }

    #[AsEventListener(event: KnpMenuEvent::AUTH_MENU)]
    public function appAuthMenu(KnpMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $this->menuService->addAuthMenu($menu);
    }


}


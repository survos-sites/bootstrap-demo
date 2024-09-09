<?php

namespace App\EventListener;

use Survos\BootstrapBundle\Event\KnpMenuEvent;
use Survos\BootstrapBundle\Service\MenuService;
use Survos\BootstrapBundle\Traits\KnpMenuHelperInterface;
use Survos\BootstrapBundle\Traits\KnpMenuHelperTrait;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[AsEventListener(event: KnpMenuEvent::PAGE_MENU, method: 'pageMenu')]
#[AsEventListener(event: KnpMenuEvent::FOOTER_MENU, method: 'footerMenu')]
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

        foreach (['ui-alerts','cards-basic','ui-badges','ui-accordion'] as $pageCode) {
            $this->add($menu, 'app_page', [
                'code' => $pageCode],
                label: $pageCode,
                icon: 'tabler:badge');
        }

//        $this->add($menu, 'app_homepage');
        // for nested menus, don't add a route, just a label, then use it for the argument to addMenuItem

        $nestedMenu = $this->addSubmenu($menu, 'Credits');

        foreach (['bundles', 'javascript'] as $type) {
            // $this->addMenuItem($nestedMenu, ['route' => 'survos_base_credits', 'rp' => ['type' => $type], 'label' => ucfirst($type)]);
            $this->addMenuItem($nestedMenu, ['uri' => "#$type", 'label' => ucfirst($type)]);
        }
    }

    public function footerMenu(KnpMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $options = $event->getOptions();
        $subMenu = $this->addSubmenu($menu, 'github');
        $this->add($subMenu, uri: 'https://github.com');
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


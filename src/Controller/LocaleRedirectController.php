<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class LocaleRedirectController extends AbstractController
{
    #[Route('/', name: 'app_locale_redirect')]
    public function __invoke(
        Request $request
    ): RedirectResponse
    {
        return $this->redirectToRoute('app_homepage', [
            '_locale' => $request->getLocale(),
        ]);
    }
}

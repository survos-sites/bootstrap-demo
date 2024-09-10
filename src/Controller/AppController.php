<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}')]
class AppController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function home(): Response
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
    #[Route('/page/{code}', name: 'app_page')]
    public function page(string $code): Response
    {
        return $this->render("pages/$code.html.twig", [
        ]);
    }

    #[Route('/search/{table}', name: 'app_search')]
    public function search(string $table,
    #[MapQueryParameter] string $q=null
    ): Response
    {
        return new Response("Searching table $table for $q");
        return $this->render("pages/$code.html.twig", [
        ]);
    }

}

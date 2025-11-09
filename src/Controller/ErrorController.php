<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    public function notFound(): Response
    {
        return $this->render(
            'bundles/TwigBundle/Exception/error404.html.twig',
            [],
            new Response('', 404)
        );
    }
}

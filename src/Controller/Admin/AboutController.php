<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    #[Route('/sobre-mi', name: 'app_sobre_mi')]
    public function index(): Response
    {
        return $this->render('about/sobre_mi.html.twig');
    }
}

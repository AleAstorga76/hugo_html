<?php
// src/Controller/ContactoController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactoController extends AbstractController
{
    #[Route('/contacto', name: 'app_contacto')]
    public function index(): Response
    {
        return $this->render('contacto/index.html.twig');
    }
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/movie/', name: 'movie_index')]
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
           'website' => 'Wild Series',
        ]);
    }
}

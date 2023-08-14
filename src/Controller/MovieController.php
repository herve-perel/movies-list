<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movie', name: 'movie_')]
class MovieController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();

        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route('/{id}', methods: ['GET'], requirements: ['id' => '\d+'], name: 'show')]
    public function show(int $id, MovieRepository $movieRepository): Response
    {
        $movie = $movieRepository->findOneBy(['id' => $id]);

        if (!$movie) {
            throw $this->createNotFoundException(
                'No movie with id : '.$id.' found in movie\'s table.'
            );
        }

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Movie;
use App\Entity\Season;
use App\Repository\MovieRepository;
use App\Form\MovieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/new', name: 'new')]

    public function new(Request $request, MovieRepository $movieRepository): Response

    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $movieRepository->save($movie, true);
            return $this->redirectToRoute('movie_index');
        }

        return $this->render('movie/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{movie}/season/{season}/episode/{episode}',  methods: ['GET'], name: 'episode_show')]
    // #[Entity('program', options: ['mapping' => ['movie_id' => 'id']])]
    // #[Entity('program', options: ['mapping' => ['season_id' => 'id']])]
    // #[Entity('comment', options: ['mapping' => ['episode_id' => 'id']])]
    public function showEpisode(Movie $movie, Season $season, Episode $episode): Response
    {
        return $this->render('movie/episode_show.html.twig', [
            'movie' => $movie->getId(),
            'season' => $season->getId(),
            'episode' => $episode->getId(),
        ]);
    }

    #[Route('/{movie}/season/{season}',  methods: ['GET'], name: 'season_show')]
    public function showSeason(Movie $movie, Season $season): Response
    {
        return $this->render('movie/seasonShow.html.twig', [
            'movie' => $movie,
            'season' => $season,
        ]);
    }

    #[Route('/{id}', methods: ['GET'], requirements: ['id' => '\d+'], name: 'show')]
    public function show(Movie $movie): Response
    {
        if (!$movie) {
            throw $this->createNotFoundException(
                'No movie with id : ' . $movie . ' found in movie\'s table.'
            );
        }

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }
}

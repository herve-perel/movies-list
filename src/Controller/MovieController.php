<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Movie;
use App\Entity\Season;
use App\Repository\MovieRepository;
use App\Form\MovieType;
use App\Service\ProgramDuration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Mailer\MailerInterface;

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

    public function new(Request $request, MovieRepository $movieRepository, SluggerInterface $slugger, MailerInterface $mailer): Response

    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug(($movie->getTitle()));
            $movie->setSlug($slug);
            $movieRepository->save($movie, true);

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('movie/newMovieEmail.html.twig', ['movie' => $movie]));  
            $mailer->send($email);

            $this->addFlash('success', 'The new movie has been created');
            return $this->redirectToRoute('movie_index');
        }

        return $this->render('movie/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{slug}/season/{season}',  methods: ['GET'], name: 'season_show')]
    public function showSeason(Movie $movie, Season $season): Response
    {
        return $this->render('movie/seasonShow.html.twig', [
            'movie' => $movie,
            'season' => $season,
        ]);
    }

    #[Route('/{slug}', methods: ['GET'], name: 'show')]
    public function show(Movie $movie, ProgramDuration $programDuration): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'programDuration' => $programDuration->calculate($movie)
        ]);
    }

    #[Route('/{movieSlug}/season/{season}/episode/{episodeSlug}',  methods: ['GET'], name: 'episode_show')]
    public function showEpisode(Movie $movie, Season $season, Episode $episode): Response
    {
        return $this->render('movie/episode_show.html.twig', [
            'movie' => $movie,
            'season' => $season,
            'episode' => $episode,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Movie $movie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $movie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($movie);
            $entityManager->flush();
            $this->addFlash('danger', 'Le film a bien été supprimé');
        }

        return $this->redirectToRoute('movie_index', [], Response::HTTP_SEE_OTHER);
    }
}

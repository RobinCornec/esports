<?php

namespace App\Controller;

use App\Repository\ContestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/')]
    public function index(ContestRepository $matchesRepository): Response
    {
        $matches = $matchesRepository->findBy([], [
            'ended' => 'asc',
            'beginAt' => 'desc',
        ]);
        return $this->render('index.html.twig', ['matches' => $matches]);
    }
    #[Route('/test')]
    public function test(ContestRepository $matchesRepository): Response
    {
        return $this->render('test/index.html.twig');
    }
}
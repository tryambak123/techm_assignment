<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
class GuestController extends AbstractController
{
    #[Route('/guest', name: 'app_guest')]
	public function index(UserRepository $userRepository): Response
    {
        return $this->render('guest/index.html.twig', [
            'guests' => $userRepository->findAll(),
        ]);
    }
}

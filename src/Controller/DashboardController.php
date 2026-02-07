<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

       
        if (!$user) {
            return new Response("<html><body><h1>Accès restreint</h1><p>Tu dois être connecté. Si tu n'as pas de login, commente cette sécurité dans le code.</p></body></html>");
        }

        $session = $user->getGameSession();

        return $this->render('dashboard/index.html.twig', [
            'user' => $user,
            'session' => $session,
        ]);
    }
}

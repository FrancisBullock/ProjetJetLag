<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/seeker/ask/{id}', name: 'app_seeker_ask')]
    public function ask(Question $question, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $session = $user->getGameSession();

        // On marque la question comme consommée
        $session->addConsumedQuestion($question);
        $em->flush();

        // Redirection vers l'attente de réponse du Hider
        return $this->redirectToRoute('app_hider_wait_answer', ['id' => $question->getId()]);
    }

    #[Route('/seeker/questions/{categoryId}', name: 'app_seeker_list_questions')]
    public function list(int $categoryId, QuestionRepository $qRepo): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $session = $user->getGameSession();

        // Récupère les questions de la catégorie non encore consommées
        $questions = $qRepo->findAvailableForSession($categoryId, $session->getId());

        return $this->render('question/list.html.twig', [
            'questions' => $questions
        ]);
    }
}

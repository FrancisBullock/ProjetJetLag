<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\User;

use App\Repository\CardRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    #[Route('/card/use-bonus/{id}', name: 'app_card_use_bonus')]
    public function useBonus(Card $card, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $session = $user->getGameSession();

        if ($card->getType() === 'Bonus') {
            $session->setHiderBonusTime($session->getHiderBonusTime() + $card->getEffectValue());
            $user->removeCard($card); // Défausse
            $em->flush();
        }

        return $this->redirectToRoute('app_hider_dashboard');
    }

    #[Route('/card/randomize/{powerUpId}/{currentQuestionId}', name: 'app_card_randomize')]
    public function randomize(
        int $powerUpId, 
        int $currentQuestionId, 
        CardRepository $cardRepo, 
        QuestionRepository $qRepo,
        EntityManagerInterface $em
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        $session = $user->getGameSession();
        
        $powerUp = $cardRepo->find($powerUpId);
        $currentQuestion = $qRepo->find($currentQuestionId);

        if ($powerUp && $currentQuestion) {
            $user->removeCard($powerUp); // Défausse systématique
            
            // TEMPORISATION : On rend la question à nouveau disponible
            $session->removeConsumedQuestion($currentQuestion);

            // Tirage d'une nouvelle question (Logique simplifiée pour l'exemple)
            $newQuestion = $qRepo->findOneBy(['category' => $currentQuestion->getCategory()]);
            
            if ($newQuestion) {
                $session->addConsumedQuestion($newQuestion);
                $em->flush();
                return $this->redirectToRoute('app_question_show', ['id' => $newQuestion->getId()]);
            }
            $em->flush();
        }
        return $this->redirectToRoute('app_game_status');
    }
}
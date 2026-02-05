<?php
namespace App\Controller;

use App\Entity\Question;
use App\Entity\User;
use App\Repository\CardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game/resolve/{id}', name: 'game_resolve_question')]
    public function resolveQuestion(Question $question, CardRepository $cardRepository)
    {
        $categoryName = $question->getCategory()->getName();
        $cardsToDraw = 0;
        $cardsToKeep = 0;

        // Récompense de la personne qui se cache
        switch ($categoryName) {
            case 'Photo':
                $cardsToDraw = 1;
                $cardsToKeep = 1;
                break;
                //Ces deux catégories donnent les mèmes récompenses dans le jeu
            case 'Thermometer'||'Radar':
                $cardsToDraw = 2;
                $cardsToKeep = 1;
                break;
            case 'Tentacles':
                $cardsToDraw = 4;
                $cardsToKeep = 2;
                break;
            default:
                $cardsToDraw = 3;
                $cardsToKeep = 1;
        }
        // On récupère des cartes aléatoires (Bonus de Temps, Veto, Malédictions, etc.)
        $drawnCards = $cardRepository->findRandomCards($cardsToDraw);

        return $this->render('game/draw_cards.html.twig', [
            'question' => $question,
            'drawnCards' => $drawnCards,
            'keepCount' => $cardsToKeep,
            'category' => $categoryName
        ]);
    }
}
?php

namespace App\DataFixtures;

use App\Entity\Card;
use App\Entity\Category;
use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- 1. DÉFINITION DES CATÉGORIES (Source: jetlag.neocities.org) ---
        $categoriesData = [
            'Matching'    => ['draw' => 3, 'pick' => 1, 'time' => 5],
            'Measuring'   => ['draw' => 3, 'pick' => 1, 'time' => 5],
            'Thermometer' => ['draw' => 2, 'pick' => 1, 'time' => 5],
            'Radar'       => ['draw' => 2, 'pick' => 1, 'time' => 5],
            'Tentacles'   => ['draw' => 4, 'pick' => 2, 'time' => 5],
            'Photos'      => ['draw' => 1, 'pick' => 1, 'time' => 15],
        ];

        $categories = [];
        foreach ($categoriesData as $name => $config) {
            $category = new Category();
            $category->setName($name);
            // Si tu as ajouté ces champs dans ton entité, décommente les lignes suivantes :
            // $category->setCostDraw($config['draw']);
            // $category->setCostPick($config['pick']);
            // $category->setTimeLimit($config['time']);
            $manager->persist($category);
            $categories[$name] = $category;
        }

        // --- 2. QUESTIONS : RADAR (Distances personnalisées) ---
        $distancesRadar = ['250m', '500m', '1km', '3km', '5km', '10km', '25km', '50km', '100km', '200km'];
        foreach ($distancesRadar as $dist) {
            $this->createQuestion($manager, $categories['Radar'], 
                "Radar $dist", 
                "Hider : Es-tu dans un rayon de $dist autour de la position actuelle du Seeker ?");
        }

        // --- 3. QUESTIONS : THERMOMETER (Logique de déplacement) ---
        $mouvementsThermo = ['500m', '5km', '15km', '50km'];
        foreach ($mouvementsThermo as $move) {
            $this->createQuestion($manager, $categories['Thermometer'], 
                "Thermometer $move", 
                "Après que le Seeker a voyagé $move, le Seeker est-il plus proche ou plus loin du Hider ?");
        }

        // --- 4. QUESTIONS : TENTACLES (Logique de proximité) ---
        //$distancesTentacles = ['1km', '15km'];//Simplification . A modifier quand je voudrai revenir sur ce que j'ai fait plus tard
        $distancesTentacles=['15km']
        $éléments['gares','musée','hopitaux'];
        foreach ($distancesTentacles as $dist) {
            foreach($éléments as $élément){
            $this->createQuestion($manager, $categories['Tentacles'], 
                "Tentacles $dist", 
                "Parmi les éléments à $dist du Seeker, quel est le sujet le plus proche du Hider ? (Répondre 'Aucun' si Hider est plus loin que $dist)");
        
                }
            }

        // --- 5. QUESTIONS : MEASURING (Comparaison Hider/Seeker) ---
        $sujetsMeasuring = ['Aéroport ', 'Gare ', 'Mer/Océan', 'Parc', 'Musée'];
        foreach ($sujetsMeasuring as $sujet) {
            $this->createQuestion($manager, $categories['Measuring'], 
                "Measuring $sujet", 
                "Hider : Es-tu plus proche ou plus loin d'un(e) $sujet que le Seeker ?");
        }

        // --- 6. QUESTIONS : MATCHING (Reconnaissance) ---
        $sujetsMatching = ['Nom de la station (longueur)', '1ere division Administratives','2ème division Administratives','Ligne de transit'];
        foreach ($sujetsMatching as $sujet) {
            $this->createQuestion($manager, $categories['Matching'], 
                "Match sur : $sujet", 
                "Est-ce que ton élément [$sujet] correspond à celui du Seeker ?");
        }

        // --- 7. QUESTIONS : PHOTOS (Eléments visuels) ---
        $sujetsPhotos = ['Un arbre', 'Le ciel', 'Quai de train', 'Intérieur de restaurant', 'Structure la plus haute','Selfie','5 Batiments','2 Batiement','Plus haut batiment visible'];
        foreach ($sujetsPhotos as $sujet) {
            $this->createQuestion($manager, $categories['Photos'], 
                "Photo de : $sujet", 
                "Prends une photo de : $sujet et envoie-la au Seeker.");
        }

        // --- 8. CARTES DU DECK (Récompenses) ---
        $cartes = ['Standard Ping', 'Directional Ping', 'Freeze (10 min)', 'Veto', 'Double Time (30 min)'];
        foreach ($cartes as $nom) {
            $card = new Card();
            $card->setName($nom);
            $manager->persist($card);
        }

        $manager->flush();
    }

    private function createQuestion(ObjectManager $manager, Category $category, string $title, string $desc): void
    {
        $q = new Question();
        $q->setTitle($title);
        $q->setDescription($desc);
        $q->setCategory($category);
        $manager->persist($q);
    }
}
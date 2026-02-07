<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Card;
use App\Entity\Category;
use App\Entity\Question;
use App\Entity\GameSession;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Création de l'utilisateur (Indispensable pour la session)
        $user = new User();
        $user->setEmail('hider@test.com');
        $password = $this->hasher->hashPassword($user, 'password');
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        // 2. Création de la Session liée
        $session = new GameSession();
        $session->setUser($user);
        $session->setStartTime(new \DateTime());
        $session->setHiderBonusTime(0);
        $manager->persist($session);

        // --- 3. DÉFINITION DES CATÉGORIES ---
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
            $manager->persist($category);
            $categories[$name] = $category;
        }

        // --- 4. QUESTIONS : RADAR ---
        $distancesRadar = ['250m', '500m', '1km', '3km', '5km', '10km', '25km', '50km', '100km', '200km'];
        foreach ($distancesRadar as $dist) {
            $this->createQuestion($manager, $categories['Radar'], "Radar $dist", "Le hider est-il dans un rayon de $dist ?");
        }

        // --- 5. QUESTIONS : THERMOMETER ---
        $mouvementsThermo = ['500m', '5km', '15km', '50km'];
        foreach ($mouvementsThermo as $move) {
            $this->createQuestion($manager, $categories['Thermometer'], "Thermometer $move", "Après $move, le Seeker est-il plus proche ?");
        }

        // --- 6. QUESTIONS : TENTACLES ---
        $distancesTentacles = ['15km'];
        $elements = ['gares', 'musée', 'hopitaux']; // Correction ici
        foreach ($distancesTentacles as $dist) {
            foreach ($elements as $element) {
                $this->createQuestion($manager, $categories['Tentacles'], "Tentacles $dist", "Parmi les $element à $dist, quel est le plus proche ?");
            }
        }

        // --- 7. QUESTIONS : MEASURING ---
        $sujetsMeasuring = ['Aéroport ', 'Gare ', 'Mer/Océan', 'Parc', 'Musée'];
        foreach ($sujetsMeasuring as $sujet) {
            $this->createQuestion($manager, $categories['Measuring'], "Measuring $sujet", "Es-tu plus proche d'un(e) $sujet ?");
        }

        // --- 8. QUESTIONS : MATCHING ---
        $sujetsMatching = ['Nom de station', 'Zone Admin 1', 'Zone Admin 2', 'Transit'];
        foreach ($sujetsMatching as $sujet) {
            $this->createQuestion($manager, $categories['Matching'], "Match : $sujet", "Ton élément [$sujet] correspond-il ?");
        }

        // --- 9. QUESTIONS : PHOTOS ---
        $sujetsPhotos = ['Un arbre', 'Le ciel', 'Quai', 'Restaurant', 'Selfie'];
        foreach ($sujetsPhotos as $sujet) {
            $this->createQuestion($manager, $categories['Photos'], "Photo : $sujet", "Envoie une photo de : $sujet.");
        }

        // --- 10. CARTES DU DECK ---
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
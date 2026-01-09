<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Evenement;
use App\Entity\Avis;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'un admin
        $admin = new User();
        $admin->setEmail('admin@test.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'adminpass'));
        $manager->persist($admin);

        // Création d'un responsable
        $responsable = new User();
        $responsable->setEmail('responsable@test.com');
        $responsable->setRoles(['ROLE_RESPONSABLE']);
        $responsable->setPassword($this->passwordHasher->hashPassword($responsable, 'resp1234'));
        $manager->persist($responsable);

        // Création d'un utilisateur standard
        $user = new User();
        $user->setEmail('user@test.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user1234'));
        $manager->persist($user);

        // --- EVENEMENTS ---
        $event1 = new Evenement();
        $event1->setTitre('Séminaire Tech');
        $event1->setDate(new \DateTime('+7 days'));
        $event1->setResponsable($responsable);
        $manager->persist($event1);

        $event2 = new Evenement();
        $event2->setTitre('Lancement Produit');
        $event2->setDate(new \DateTime('+14 days'));
        $event2->setResponsable($responsable);
        $manager->persist($event2);

        // --- AVIS ---
        $avis1 = new Avis();
        $avis1->setNote(5);
        $avis1->setCommentaire('Super événement !');
        $avis1->setAccepte(true); // déjà modéré pour test
        $avis1->setCreatedAt(new \DateTime());
        $avis1->setUpdatedAt(new \DateTime());
        $avis1->setUtilisateur($user);
        $avis1->setEvenement($event1);
        $manager->persist($avis1);

        $avis2 = new Avis();
        $avis2->setNote(4);
        $avis2->setCommentaire('Très instructif.');
        $avis2->setAccepte(false); // non modéré
        $avis2->setCreatedAt(new \DateTime());
        $avis2->setUpdatedAt(new \DateTime());
        $avis2->setUtilisateur($user);
        $avis2->setEvenement($event2);
        $manager->persist($avis2);

        $avis3 = new Avis();
        $avis3->setNote(3);
        $avis3->setCommentaire('Bien mais peut mieux faire.');
        $avis3->setAccepte(true);
        $avis3->setCreatedAt(new \DateTime());
        $avis3->setUpdatedAt(new \DateTime());
        $avis3->setUtilisateur($responsable); // responsable peut aussi laisser un avis
        $avis3->setEvenement($event1);
        $manager->persist($avis3);

        $manager->flush();
    }
}

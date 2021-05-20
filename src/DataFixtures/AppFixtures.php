<?php

namespace App\DataFixtures;

use App\Entity\Droit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
         $droit = new Droit();
         $droit->setLibelle('Supprimer');
         $manager->persist($droit);
        $droit = new Droit();
        $droit->setLibelle('Modifier');
        $manager->persist($droit);
        $droit = new Droit();
        $droit->setLibelle('Lire');
        $manager->persist($droit);
        $droit = new Droit();
        $droit->setLibelle('Ecrire');
        $manager->persist($droit);
        $manager->flush();


        $manager->flush();
    }
}

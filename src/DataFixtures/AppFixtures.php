<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Droit;
use App\Entity\DroitDash;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
         $this->passwordEncoder = $passwordEncoder;
    }

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
        $droitDash= new DroitDash();
        $droitDash->setLibelle('CreateDashboard');
        $manager->persist($droitDash);
        $droitDash= new DroitDash();
        $droitDash->setLibelle('ConfigDashboard');
        $manager->persist($droitDash);
        $droitDash = new DroitDash();
        $droitDash->setLibelle('DeleteDashboard');
        $manager->persist($droitDash);
        $droitDash = new DroitDash();
        $droitDash->setLibelle('addUserToDashboard');
        $manager->persist($droitDash);

        $user = new User();
        $user->setEmail('samy.bury@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'Balak2012?'));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();

    }
}

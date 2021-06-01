<?php

namespace App\DataFixtures;

use App\Entity\Dashboard;
use App\Entity\TravaillerSur;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Droit;
use App\Entity\DroitDash;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Service\AuthentificationManager;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $authManage;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder,AuthentificationManager $authManage)
    {
         $this->passwordEncoder = $passwordEncoder;
         $this->authManage = $authManage;
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

        $user = new User($this->authManage, $this->passwordEncoder);
        $user->setEmail('samy.bury@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'Balak2012?'));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();

        $dashboard = new Dashboard();
        $dashboard->setName("Among-friends");

        //ajouter un utilisateur au dashboard
        $user = new User($this->authManage, $this->passwordEncoder);
        $user->setEmail("sebastien.dutoit@gmail.com");

        $manager->persist($user);

        $dashboard->addUser($user, $manager);
        $manager->persist($dashboard);

        $manager->flush();


        $user = new User($this->authManage, $this->passwordEncoder);
        $user->setEmail("Tony.Mon@gmail.com");
        $manager->persist($user);
        $manager->flush();


    }
}

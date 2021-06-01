<?php

namespace App\Controller;
use App\Services\AuthentificationManager;
use App\Entity\Dashboard;
use App\Entity\Gerer;
use App\Entity\PossederDroitDash;
use App\Entity\User;
use App\Repository\DroitRepository;
use App\Repository\PossederDroitDashRepository;
use Doctrine\ORM\NonUniqueResultException;
use Swift_Mailer;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\DashboardRepository;
use App\Repository\DroitDashRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{


    /**
     * @Route("/admin/User", name="users")
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param AuthentificationManager $authManage
     * @param MailerInterface $mailer
     * @param Request $request
     * @param UserRepository $userRepository
     * @param DashboardRepository $dashboardRepository
     * @param DroitDashRepository $droitDashRepository
     * @param EntityManagerInterface $em
     * @param PossederDroitDashRepository $posRepo
     * @return Response
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function MakeUser(UserPasswordEncoderInterface $passwordEncoder,AuthentificationManager $authManage,  MailerInterface $mailer,Request $request,UserRepository $userRepository,DashboardRepository $dashboardRepository, DroitDashRepository $droitDashRepository,EntityManagerInterface $em, PossederDroitDashRepository $posRepo): Response
    {


        $erreur=null;
        $droitAjouter = array();

        /* recuperer les dashboards*/

        $users = $userRepository->findAll();

        /* recuperer les users*/



        /* creer un leader-dash */
        $emailLeader = $request->request->get('email');

        $createDash = $request->request->get('1');
        $configDash = $request->request->get('2');
        $deleteDash = $request->request->get('3');
        $addUser = $request->request->get('4');
        $eCreateDash = $request->request->get('e1');
        $eConfigDash = $request->request->get('e2');
        $eDeleteDash = $request->request->get('e3');
        $eAddUser = $request->request->get('e4');
        $existingUserId = $request->request->get("existingUser");
        $existingUser = $userRepository->find((int)$existingUserId);
        $isAdmin = $request->request->get('isAdmin');


        if ($emailLeader != null){

            $user = new User($authManage, $passwordEncoder);
            $user->setEmail($emailLeader);
            $em->persist($user);
            if ($isAdmin=== 'on'){
                $user->setRoles(['ROLE_ADMIN']);
            }



            if($createDash === 'on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($user);
                $possederDroitDash->setDroitDash($droitDashRepository->findOneByLibelle('CreateDashboard'));
                $em->persist($possederDroitDash);
            }
            if($configDash==='on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($user);
                $possederDroitDash->setDroitDash($droitDashRepository->findOneByLibelle('ConfigDashboard'));
                $em->persist($possederDroitDash);
            }
            if($deleteDash==='on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($user);
                $possederDroitDash->setDroitDash($droitDashRepository->findOneByLibelle('DeleteDashboard'));
                $em->persist($possederDroitDash);
            }
            if($addUser==='on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($user);
                $possederDroitDash->setDroitDash($droitDashRepository->findOneByLibelle('addUserToDashboard'));
                $em->persist($possederDroitDash);
            }
            if ($deleteDash==null && $configDash==null&& $createDash==null){
                $erreur = "aucun droit n'a été accorder";

            }
            else{

                $em->flush();
                $email = new Email();
                $email->to($emailLeader)
                    ->from("samy.bury@gmail.com")
                    ->subject('hello Email')
                    ->html("<h1>votre mot de passe est : ".$user->getReelPassword()."</h1>");

                $mailer->send($email);

            }

        }
        elseif ($existingUser!==null){
            $user = $userRepository->findByEmail($existingUser);
            if($eCreateDash === 'on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($existingUser);
                $possederDroitDash->setDroitDash($droitDashRepository->findOneByLibelle('CreateDashboard'));
                $em->persist($possederDroitDash);
                $droitAjouter[]= "Create dashboard ";
            }
            if($eConfigDash==='on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($existingUser);
                $possederDroitDash->setDroitDash($droitDashRepository->findOneByLibelle('ConfigDashboard'));
                $em->persist($possederDroitDash);
                $droitAjouter[]= "Configurer dashboard ";
            }
            if($eDeleteDash==='on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($existingUser);
                $possederDroitDash->setDroitDash($droitDashRepository->findOneByLibelle('DeleteDashboard'));
                $em->persist($possederDroitDash);
                $droitAjouter[]= "Delete dashboard ";
            }
            if($eAddUser==='on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($existingUser);
                $possederDroitDash->setDroitDash($droitDashRepository->findOneByLibelle('addUserToDashboard'));
                $em->persist($possederDroitDash);
                $droitAjouter[]= "add user to dashboard";
            }
            if ($eDeleteDash==null && $eConfigDash==null&& $eCreateDash==null){
                $erreur = "aucun droit n'a été accorder";

            }
            else{
                $bodyEmail = "<h1>les droits ";
                foreach ($droitAjouter as $undroit){
                    $bodyEmail.=$undroit;
                }
                $bodyEmail.="</h1>";


                $email = new Email();
                $email->to($existingUser->getEmail())
                    ->from("samy.bury@gmail.com")
                    ->subject('ajout de droit')
                    ->html($bodyEmail);

                $mailer->send($email);

                $em->flush();
            }
        }






        return $this->render('user/dashLeader.html.twig',["users"=>$users,"erreur"=>$erreur]);
    }

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param AuthentificationManager $authManage
     * @param MailerInterface $mailer
     * @param $idDash
     * @param DroitRepository $droitRepository
     * @param Request $request
     * @param UserRepository $userRepository
     * @param DashboardRepository $dashboardRepository
     * @param DroitDashRepository $droitDashRepository
     * @param EntityManagerInterface $em
     * @param PossederDroitDashRepository $posRepo
     * @return Response
     * @throws TransportExceptionInterface
     * @Route("/userMaker/{idDash}", name="userMaker")
     */
    public function MakeUserDashboard(UserPasswordEncoderInterface $passwordEncoder, AuthentificationManager $authManage, MailerInterface $mailer,$idDash,DroitRepository $droitRepository, Request $request,DashboardRepository $dashboardRepository,EntityManagerInterface $em): Response
    {
        $email=$request->request->get('email');
        $password=$request->request->get('password');

        if ($email!=null && $password!=null){
            $user= new User($authManage, $passwordEncoder);
            $user->setEmail($email);

            $em->persist($user);

            $dashboard = $dashboardRepository->find($idDash);
            foreach ($dashboard->getColonnes() as $uneColonne){
                $readColonne = $request->request->get('read'.$uneColonne->getName());
                $writeColonne = $request->request->get('write'.$uneColonne->getName());
                $modifyColonne = $request->request->get('modify'.$uneColonne->getName());
                $deleteColonne = $request->request->get('delete'.$uneColonne->getName());
                if ($readColonne !==null){
                    $gerer = new Gerer();
                    $gerer->setUser($user);
                    $gerer->setColonne($uneColonne);
                    $lire=$droitRepository->findOneByName('Lire');
                    $gerer->setDroit($lire);
                    $em->persist($gerer);
                }
            }
            $emails= 'samy.bury@gmail.com';
            $email= new Email();
            $email->from("samy.bury@gmail.com")
                ->to($emails)
                ->priority(Email::PRIORITY_HIGH)
                ->subject('le sujet')

                ->html("<h1>votre mot de passe est : ".$password."</h1>");
            $mailer->send($email);


        }
    }
}

<?php

namespace App\Controller;

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
     * @param Swift_Mailer $mailer
     * @param Request $request
     * @param UserRepository $userRepository
     * @param DashboardRepository $dashboardRepository
     * @param DroitDashRepository $droitDashRepository
     * @param EntityManagerInterface $em
     * @param PossederDroitDashRepository $posRepo
     * @return Response
     * @throws NonUniqueResultException
     */
    public function MakeUser(UserPasswordEncoderInterface $passwordEncoder,   MailerInterface $mailer,Request $request,UserRepository $userRepository,DashboardRepository $dashboardRepository, DroitDashRepository $droitDashRepository,EntityManagerInterface $em, PossederDroitDashRepository $posRepo): Response
    {


        $erreur=null;
        $mailObject = "Among-tool password";
        $mailMessage = "Votre mot de passe pour acceder à votre espace Among-tool est : ";
        $mailMessage2 = " Vous pourrez ensuite changer votre mot de passe depuis votre espace";

        /* recuperer les dashboards*/

        $users = $userRepository->findAll();

        /* recuperer les users*/



        /* creer un leader-dash */
        $emailLeader = $request->request->get('email');
        $passwordLeader = $request->request->get('password');
        $createDash = $request->request->get('1');
        $configDash = $request->request->get('2');
        $deleteDash = $request->request->get('3');
        $addUser = $request->request->get('4');
        $existingUser = $request->request->get("existingUser");
        $isAdmin = $request->request->get('isAdmin');



        if ($emailLeader != null && $passwordLeader!=null ){

            $user = new User();
            $user->setEmail($emailLeader);
            $user->setPassword($passwordEncoder->encodePassword($user,$passwordLeader));
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
                    ->html("<h1>votre mot de passe est : ".$passwordLeader."</h1>");







                $mailer->send($email);

            }

            }
        elseif ($existingUser!==null){
            $user = $userRepository->findByEmail($existingUser);
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
                $message = (new \Swift_Message('hello Email'))
                    ->setFrom("samy.bury@gmail.com")
                    ->setTo($emailLeader)


                    ->setBody(
                        $this->renderView('email/newUser.html.twig',
                            ['name'=>$emailLeader, 'password'=>$passwordLeader]
                        )
                    );
                $mailer->send($message);
            }
        }






        return $this->render('user/dashLeader.html.twig',["users"=>$users,"erreur"=>$erreur]);
    }

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Swift_Mailer $mailer
     * @param Request $request
     * @param UserRepository $userRepository
     * @param DashboardRepository $dashboardRepository
     * @param DroitDashRepository $droitDashRepository
     * @param EntityManagerInterface $em
     * @param PossederDroitDashRepository $posRepo
     * @return Response
     * @Route("/userMaker/{idDash}", name="userMaker")
     */
    public function MakeUserDashboard(UserPasswordEncoderInterface $passwordEncoder,MailerInterface $mailer,$idDash,DroitRepository $droitRepository, Request $request,UserRepository $userRepository,DashboardRepository $dashboardRepository, DroitDashRepository $droitDashRepository,EntityManagerInterface $em, PossederDroitDashRepository $posRepo): Response
    {
        $email=$request->request->get('email');
        $password=$request->request->get('password');

        if ($email!=null && $password!=null){
            $user= new User();
            $user->setEmail($email);
            $user->setPassword($passwordEncoder->encodePassword($user,$password));
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

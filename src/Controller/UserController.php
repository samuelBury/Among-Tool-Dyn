<?php

namespace App\Controller;

use App\Entity\Dashboard;
use App\Entity\Gerer;
use App\Entity\PossederDroitDash;
use App\Entity\User;
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
     * @Route("/MakeUser/{idDash}", name="userMaker")
     * @param DashboardRepository $dashRepo
     * @param $idDash
     * @return Response
     */
    public function index(DashboardRepository $dashRepo, $idDash,Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $droits = array();
            $dash = $dashRepo->find($idDash);
            foreach ($dash->getColonnes() as $uneColonne){
                $lire =$request->request->get('lire'.$uneColonne->getName());
                if (issset($lire)){
                    $droits[]= $lire;
                }
                $ajoute =$request->request->get('ajout'.$uneColonne->getName());
                if (issset($ajoute)){
                    $droits[]= $ajoute;
                }
                $modifier =$request->request->get('modif'.$uneColonne->getName());
                if (issset($modifier)){
                    $droits[]= $modifier;
                }
                $supprimer =$request->request->get('sup'.$uneColonne->getName());
                if (issset($supprimer)){
                    $droits[]= $supprimer;
                }


            }
        return $this->render('user/index.html.twig',['dash'=>$dash]);
    }

    /**
     * @Route("/User", name="users")
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param MailerInterface $mailer
     * @param Request $request
     * @param UserRepository $userRepository
     * @param DashboardRepository $dashboardRepository
     * @param DroitDashRepository $droitDashRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function MakeUser(UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer,Request $request,UserRepository $userRepository,DashboardRepository $dashboardRepository, DroitDashRepository $droitDashRepository,EntityManagerInterface $em): Response
    {



        $mailObject = "Among-tool password";
        $mailMessage = "Votre mot de passe pour acceder à votre espace Among-tool est : ";
        $mailMessage2 = " Vous pourrez ensuite changer votre mot de passe depuis votre espace";

        /* recuperer les dashboards*/
        $dashs=$dashboardRepository->findAll();
        $users = $userRepository->findAll();

        /* recuperer les users*/



        /* creer un leader-dash */
        $emailLeader = $request->request->get('email');
        $passwordLeader = $request->request->get('password');
        $pseudoLeader = $request->request->get('pseudo');
        $dashboardId = $request->request->get('dashboard');

        if ($emailLeader != null && $passwordLeader!=null && $pseudoLeader!=null){

            $user = new User();
            $user->setEmail($emailLeader);
            $user->setPassword($passwordEncoder->encodePassword($user,$passwordLeader));
            dump($user->getPassword());
            $user->setPseudo($pseudoLeader);

            $possederDroitDash = new PossederDroitDash();
            if($dashboardId!=null){
                $dashboard= new Dashboard();
                $dashboard->setName($pseudoLeader.'dash');
                $em->persist($dashboard);
            }
            else{
                $possederDroitDash->setDashboard($dashboardRepository->find((string)$dashboardId));
            }
            $possederDroitDash->setUser($user);
            $possederDroitDash->setDroitDash($droitDashRepository->find(1));

            $em->persist($user);
            $em->persist($possederDroitDash);
            $em->flush();
            /*$email = new Email();
            $email->from("samy.bury@gmail.com")
                ->to($emailLeader)
                ->priority(Email::PRIORITY_HIGH)
                ->subject($mailObject)
                ->text($mailMessage.$passwordLeader.$mailMessage2)
                ->html("<h1>votre mot de passe est : ".$passwordLeader."</h1>");
            $mailer->send($email);
            */
        }








        return $this->render('user/dashLeader.html.twig',["dashboards"=>$dashs, "users"=>$users]);
    }
}

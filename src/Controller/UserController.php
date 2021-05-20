<?php

namespace App\Controller;

use App\Entity\Dashboard;
use App\Entity\Gerer;
use App\Entity\PossederDroitDash;
use App\Entity\User;
use App\Repository\PossederDroitDashRepository;
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
     * @param PossederDroitDashRepository $posRepo
     * @param UserRepository $userRepo
     * @return Response
     */
    public function MakeUser(UserPasswordEncoderInterface $passwordEncoder,  \Swift_Mailer $mailer,Request $request,UserRepository $userRepository,DashboardRepository $dashboardRepository, DroitDashRepository $droitDashRepository,EntityManagerInterface $em, PossederDroitDashRepository $posRepo): Response
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
        $configDash = $request->request->get('3');
        $deleteDash = $request->request->get('4');
        $addUser = $request->request->get('5');
        $existingUser = $request->request->get("existingUser");



        if ($emailLeader != null && $passwordLeader!=null ){

            $user = new User();
            $user->setEmail($emailLeader);
            $user->setPassword($passwordEncoder->encodePassword($user,$passwordLeader));
            $em->persist($user);



            if($createDash === 'on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($user);
                $possederDroitDash->setDroitDash($droitDashRepository->find(1));
                $em->persist($possederDroitDash);
            }
            if($configDash==='on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($user);
                $possederDroitDash->setDroitDash($droitDashRepository->find(3));
                $em->persist($possederDroitDash);
            }
            if($deleteDash==='on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($user);
                $possederDroitDash->setDroitDash($droitDashRepository->find(4));
                $em->persist($possederDroitDash);
            }
            if($addUser==='on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($user);
                $possederDroitDash->setDroitDash($droitDashRepository->find(5));
                $em->persist($possederDroitDash);
            }
            if ($deleteDash==null && $configDash==null&& $createDash==null){
                $erreur = "aucun droit n'a été accorder";

            }
            else{

                $em->flush();
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
        elseif ($existingUser!==null){
            $user = $userRepository->findByEmail($existingUser);
            if($createDash === 'on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($user);
                $possederDroitDash->setDroitDash($droitDashRepository->find(1));
                $em->persist($possederDroitDash);
            }
            if($configDash==='on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($user);
                $possederDroitDash->setDroitDash($droitDashRepository->find(3));
                $em->persist($possederDroitDash);
            }
            if($deleteDash==='on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($user);
                $possederDroitDash->setDroitDash($droitDashRepository->find(4));
                $em->persist($possederDroitDash);
            }
            if($addUser==='on'){
                $possederDroitDash = new PossederDroitDash();
                $possederDroitDash->setUser($user);
                $possederDroitDash->setDroitDash($droitDashRepository->find(5));
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
}

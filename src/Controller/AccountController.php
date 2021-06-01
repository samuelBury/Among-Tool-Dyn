<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Services\AuthentificationManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     * @param UserRepository $userRepo
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param AuthentificationManager $authentificationManager
     * @return Response
     */
    public function index(UserRepository $userRepo, Request $request, UserPasswordEncoderInterface $passwordEncoder, AuthentificationManager $authentificationManager): Response
    {
        $userId =$this->getUser()->getId();
        $user = $userRepo->find($userId);
        $verrif= false;
        $message= null;
        $emailVerif =$request->request->get("emailVerif");
        $passwordVerif = $request->request->get("passwordVerif");



        if ($user->getEmail() == $emailVerif && $passwordEncoder->isPasswordValid($user,$passwordVerif )){
            dump("c'est bon");
            $verrif = true;
            $this->redirect("accountChangePaswword");
        }
        else{
            $verrif = false;
        }



        return $this->render('account/index.html.twig', [
            'verrification' => $verrif,'message'=>$message
        ]);
    }
    /**
     * @Route("/accountChangePaswword", name="accountPassword")
     * @param UserRepository $userRepo
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param AuthentificationManager $authentificationManager
     * @return Response
     */
    public function second(UserRepository $userRepo, Request $request, UserPasswordEncoderInterface $passwordEncoder, AuthentificationManager $authentificationManager, EntityManagerInterface $em): Response
    {
        $userId =$this->getUser()->getId();
        $user = $userRepo->find($userId);

        $verrif= true;
            $newPassord= $request->request->get("newPassord");
            $newPasswordVerif = $request->request->get("newPasswordVerif");
            $message=$authentificationManager->verifMdpSecurise($newPassord);

            if ($newPassord == $newPasswordVerif && $newPassord!== null){
                if ($message=="bon"){
                    $user->setPassword($passwordEncoder->encodePassword($user, $newPassord));
                    $em->persist($user);
                    $em->flush();
                    dump($newPassord);

                }


            }
        return $this->render('account/index.html.twig', [
            'verrification' => $verrif,'message'=>$message
        ]);
        }

    /**
     * @Route("/forgetPassword", name="forgetPassword")
     * @param UserRepository $userRepo
     * @param Request $request
     * @param MailerInterface $mailer
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param AuthentificationManager $authentificationManager
     * @param EntityManagerInterface $em
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function third(UserRepository $userRepo, Request $request,MailerInterface $mailer, UserPasswordEncoderInterface $passwordEncoder, AuthentificationManager $authentificationManager, EntityManagerInterface $em): Response
    {
        $emailUser=$request->request->get("email");
        $password = $authentificationManager->chaine_aleatoire(8);


        if ($emailUser !== null){

            $user=$userRepo->findByEmail($emailUser);
            $user->setPassword($passwordEncoder->encodePassword($user, $password));
            $em->persist($user);
            $em->flush();
            $email= new Email();
            $email->from("samy.bury@gmail.com")
                ->to($emailUser)
                ->priority(Email::PRIORITY_HIGH)
                ->subject('le sujet')

                ->html("<h1>votre mot de passe est : ".$password."</h1>");
            $mailer->send($email);

        }
        return $this->render('security/forgetPassword.html.twig', [

        ]);
    }

}

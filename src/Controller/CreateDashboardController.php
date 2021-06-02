<?php

namespace App\Controller;

use App\Entity\Gerer;
use App\Entity\User;
use App\Repository\DroitRepository;
use App\Repository\GererRepository;
use App\Service\AuthentificationManager;
use Symfony\Component\Mime\Email;
use App\Entity\Colonne;
use App\Entity\Dashboard;
use App\Entity\Ligne;
use App\Entity\PossederDroitDash;
use App\Entity\TravaillerSur;
use App\Repository\CarreRepository;
use App\Repository\ColonneRepository;
use App\Repository\DashboardRepository;
use App\Repository\DroitDashRepository;
use App\Repository\LigneRepository;
use App\Repository\PossederDroitDashRepository;
use App\Repository\TravaillerSurRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateDashboardController extends AbstractController
{


    /**
     * @Route("/create/dashboard", name="createDashboard")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param DashboardRepository $dashRepo
     * @param DroitDashRepository $droitDashRepo
     * @param UserRepository $repoUser
     * @return Response
     * @throws NonUniqueResultException
     */
    public function createDash(Request $request,TravaillerSurRepository $posRepo, EntityManagerInterface $em, DashboardRepository $dashRepo,DroitDashRepository $droitDashRepo,UserRepository $repoUser): Response
    {


        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $dashboardName= $request->request->get('DashboardName');
        $alerte=array();
        if($dashboardName==null){

            $alerte[]= ['text'=>'Enter a name','type'=>'warning'];
        }
        else{
            $dashBoardUser =$this->getUser()->getDashboard($posRepo);
            $dashNameAlreadyExist = false;
            foreach ($dashBoardUser as $unDash){
                if ($unDash->getName() == $dashboardName){
                    $dashNameAlreadyExist = true;
                }
            }
            if ($dashNameAlreadyExist== true){
                $alerte []= ['text'=>'DashBoard name already exist','type'=>'warning'];
            }
            else{
                $dash = new Dashboard();
                $dash->setName($dashboardName);
                $travaillerSur = new TravaillerSur();

                $travaillerSur->setUser($repoUser->find($this->getUser()->getId()));
                $travaillerSur->setDashboard($dash);
                $em->persist($travaillerSur);
                $em->persist($dash);
                $em->flush();
                $alerte []= ['text'=>'DashBoard '.$dashboardName.' is created','type'=>'success'];


            }

        }


        return $this->render('Dashboard/Dashboard.html.twig', [
            'alertes' => $alerte,
        ]);
    }

    /**
     * @Route("/delete/dashboard/{idDash}", name="deleteDashboard")
     * @param DashboardRepository $dashRepo
     * @param $idDash
     * @param EntityManagerInterface $em
     * @return Response
     * @throws NonUniqueResultException
     */
    public function delete(DashboardRepository $dashRepo,$idDash ,EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        dump((int)$idDash);
        $dashboard =$dashRepo->findOneById((int)$idDash);
        dump($dashboard);
        foreach ($dashboard->getLignes() as $unLigne){
            foreach ($unLigne->getCarre() as $uneCase){
                $em->remove($uneCase);
            }
            $em->remove($unLigne);
        }
        foreach ($dashboard->getColonnes() as $uneColonne){
            $em->remove($uneColonne);
        }
        $em->remove($dashboard);
        $em->flush();
        $this->redirectToRoute('dashboard');
        $dashboards =$dashRepo->findAll();

        return $this->render('Dashboard/index.html.twig', [
            'dashboards' => $dashboards,
        ]);

    }

    /**
     * @Route("/dashboard/{idDash}", name="dashboardShow")
     * @param $idDash
     * @param ColonneRepository $colRepo
     * @param Request $request
     * @param DashboardRepository $dashRepo
     * @param EntityManagerInterface $em
     * @param CarreRepository $repoCase
     * @param TravaillerSurRepository $travaillerSurRepository
     * @param UserRepository $userRepository
     * @param PossederDroitDashRepository $posRepo
     * @return Response
     */
    public function show($idDash,ColonneRepository $colRepo ,Request $request,DashboardRepository $dashRepo,EntityManagerInterface $em, CarreRepository $repoCase,TravaillerSurRepository $travaillerSurRepository, UserRepository $userRepository,PossederDroitDashRepository $posRepo, GererRepository $gererRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $colonnesRempli =null;


        $dash=$dashRepo->find($idDash);


        $users = $dash->getUsers($travaillerSurRepository,$userRepository);
        $droitsDash= $this->getUser()->getDroitDashBoard($posRepo);

        dump($request);

        $user = $this->getUser();

        $droits =$user->getDroit($dash , $gererRepository);


        if(count($request->request->all())>0 && $dash!==null){


            $nbCol=count($dash->getColonnes());
            $lignes=$dash->requestToEntity($request,$nbCol,$colRepo,$idDash,$dashRepo, $repoCase);
            dump($lignes);
            foreach ($lignes as $uneLigne){
                if ( is_a($uneLigne, Ligne::class)){

                    $em->persist($uneLigne);
                    $cases = $uneLigne->getCarre();
                    foreach ($cases as $case){

                        $em->persist($case);
                    }
                    $em->flush();
                }
                else{
                    $em->persist($uneLigne);
                    $em->flush();
                }

            }

        }





        $colonnes= $colRepo->findByIdDashboard($idDash);


        if (count($dash->getColonnes())>0){
            $colonnesRempli=true;
        }

        return $this->render('Dashboard/showDashboard.html.twig', ['Dash'=>$dash,'structure'=>$colonnesRempli,'colonnes'=>$colonnes,'users'=>$users,'DroitDash'=>$droitsDash, 'Droits'=>$droits

        ]);
    }

    /**
     * @Route("/configDashboard/{idDash}", name="configDashboard")
     * @param $idDash
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param DashboardRepository $repo
     * @return Response
     */
    public function config($idDash ,Request $request,EntityManagerInterface $em, DashboardRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    dump(count($request->request->all()));
        $colonne = new Colonne();
        $dash= $repo->find($idDash);
        $colonnes =$colonne->requestToEntity($request,$idDash, $repo);






        foreach ($colonnes as $col){
            $em->persist($col);
            $em->flush();
        }






        return $this->render('Dashboard/Config.html.twig', ['idDash'=>$idDash, 'dash'=>$dash

        ]);
    }

    /**
     * @Route("/deleteColonne/{colId}", name="delCol")
     * @param $colId
     * @param ColonneRepository $colRepo
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function deleteCol($colId, ColonneRepository $colRepo, EntityManagerInterface $em): Response
    {
        $colonne = $colRepo->find($colId);
        if (isset($colonne)){
            foreach ($colonne->getCarre() as $unCarre){
                $em->remove($unCarre);
            }
            $em->remove($colonne);
            $em->flush();
            $idDash =$colonne->getDashboard()->getId();
        }



        return $this->redirectToRoute('configDashboard',['idDash'=>$idDash]);
    }

    /**
     * @Route("/deleteLigne/{idDash}/{ligneId}", name="delLigne")
     * @param $ligneId
     * @param $idDash
     * @param LigneRepository $ligneRepo
     * @param DashboardRepository $dashRepo
     * @param ColonneRepository $colRepo
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function deleteLigne($ligneId,$idDash, LigneRepository $ligneRepo,DashboardRepository $dashRepo,ColonneRepository $colRepo,EntityManagerInterface $em): Response
    {

        $ligne = $ligneRepo->find($ligneId);
        foreach ($ligne->getCarre() as $uneCase){
            $em->remove($uneCase);
        }
        $em->remove($ligne);
        $em->flush();


        $colonnesRempli =null;
        $dash= $dashRepo->find($idDash);
        $colonnes= $colRepo->findByIdDashboard($idDash);

        if (count($dash->getColonnes())>0){
            $colonnesRempli=true;
        }


        return $this->render('Dashboard/showDashboard.html.twig', ['Dash'=>$dash,'structure'=>$colonnesRempli,'colonnes'=>$colonnes]);
    }

    /**
     * @Route("/addUser/{idDash}", name="adduser")
     * @param Request $request
     * @param MailerInterface $mailer
     * @param EntityManagerInterface $em
     * @param AuthentificationManager $authentificationManager
     * @param UserPasswordEncoderInterface $encoder
     * @param ColonneRepository $colRepo
     * @param $idDash
     * @param DashboardRepository $dashRepo
     * @param TravaillerSurRepository $travaillerSurRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function addUser(Request $request,MailerInterface $mailer, DroitRepository $droitRepository,UserRepository $userRepository,EntityManagerInterface $em,AuthentificationManager $authentificationManager, UserPasswordEncoderInterface $encoder,ColonneRepository $colRepo,$idDash, DashboardRepository $dashRepo, TravaillerSurRepository $travaillerSurRepository): Response
    {
        $formulaireRempli = false;
        $droit = array();
        $dash= $dashRepo->find($idDash);
        if (isset($dash)){
            $users=$dash->getUsers($travaillerSurRepository, $userRepository);
            $colonnes = $dash->getColonnes();

        }
        $emailUser = $request->request->get('email');
        $emailUserExisting = $request->request->get('existingUser');
        $user =$userRepository->findByEmail($emailUser);

        if (($emailUser!= "" || $emailUserExisting!="") && $user == null) {


            foreach ($colonnes as $uneColonne) {

                $droitWrite = $request->request->get('write' . $uneColonne->getName());
                $readWrite = $request->request->get('read' . $uneColonne->getName());
                $modifyWrite = $request->request->get('modify' . $uneColonne->getName());
                $deleteWrite = $request->request->get('delete' . $uneColonne->getName());
                dump($droitWrite);


                if ( isset($droitWrite) || isset($readWrite) || isset($modifyWrite) || isset($deleteWrite)){

                    $formulaireRempli = true;



                }


            }

        }
        if ($formulaireRempli ==true){



            if( $emailUser != ""){
                $user = new User($authentificationManager,$encoder);
                $user->setEmail($emailUser);
                $email = new Email();
                $email->to($emailUser)
                    ->from("among.friends.messagerie@gmail.com")
                    ->subject('hello Email')
                    ->html("<h1>votre mot de passe est : ".$user->getReelPassword()."</h1>");

                $mailer->send($email);
                $em->persist($user);
                $dash->addUser($user, $em);


            }
            else{
                dump($emailUserExisting);
                $user=$userRepository->find($emailUserExisting);
                dump($user->getEmail());

                $em->persist($user);
                $dash->addUser($user, $em);




            }
            foreach ($dash->getColonnes() as $uneColonne){
                $droitWrite = $request->request->get('write' . $uneColonne->getName());
                $readWrite = $request->request->get('read' . $uneColonne->getName());
                $modifyWrite = $request->request->get('modify' . $uneColonne->getName());
                $deleteWrite = $request->request->get('delete' . $uneColonne->getName());

                if ($droitWrite === "on"){
                    dump('lol');
                    $gererDroit = new Gerer();
                    $gererDroit->setUser($user);
                    $gererDroit->setDroit($droitRepository->findOneByName("Ecrire"));
                    $gererDroit->setColonne($uneColonne);
                    $em->persist($gererDroit);
                }
                if ($readWrite === "on"){
                    $gererDroit = new Gerer();
                    $gererDroit->setUser($user);
                    $gererDroit->setDroit($droitRepository->findOneByName("Lire"));
                    $gererDroit->setColonne($uneColonne);
                    $em->persist($gererDroit);
                }
                if ($modifyWrite === "on"){
                    $gererDroit = new Gerer();
                    $gererDroit->setUser($user);
                    $gererDroit->setDroit($droitRepository->findOneByName("Modifier"));
                    $gererDroit->setColonne($uneColonne);
                    $em->persist($gererDroit);
                }
                if ($deleteWrite === "on"){
                    $gererDroit = new Gerer();
                    $gererDroit->setUser($user);
                    $gererDroit->setDroit($droitRepository->findOneByName("Supprimer"));
                    $gererDroit->setColonne($uneColonne);
                    $em->persist($gererDroit);
                }
                $em->flush();


            }







        }









    return $this->render('Dashboard/userForm.html.twig',['users'=>$users, 'Dash'=>$dash]);
    }



}

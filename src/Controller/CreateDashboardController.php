<?php

namespace App\Controller;

use App\Entity\Colonne;
use App\Entity\Dashboard;
use App\Entity\Ligne;
use App\Repository\CarreRepository;
use App\Repository\ColonneRepository;
use App\Repository\DashboardRepository;
use App\Repository\LigneRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateDashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(DashboardRepository $dashRepo): Response
    {
        $dashboards =$dashRepo->findAll();

        return $this->render('Dashboard/index.html.twig', [
            'dashboards' => $dashboards,
        ]);
    }

    /**
     * @Route("/create/dashboard", name="createDashboard")
     * @param Request $request
     * @return Response
     */
    public function createDash(Request $request, EntityManagerInterface $em, DashboardRepository $dashRepo): Response
    {

        $dashboardName= $request->request->get('DashboardName');
        $alerte=array();
        if($dashboardName==null){

            $alerte[]= ['text'=>'Enter a name','type'=>'warning'];
        }
        else{
            if ($dashRepo->findByName($dashboardName)!==null){
                $alerte []= ['text'=>'DashBoard name already exist','type'=>'warning'];
            }
            else{
                $dash = new Dashboard();
                $dash->setName($dashboardName);
                $em->persist($dash);
                $em->flush();
                $alerte []= ['text'=>'DashBoard '.$dashboardName.' is created','type'=>'success'];
            }

        }

        dump($alerte);
        dump($dashboardName);
        return $this->render('Dashboard/dashboard.html.twig', [
            'alertes' => $alerte,
        ]);
    }
    /**
     * @Route("/delete/dashboard/{idDash}", name="deleteDashboard")
     */
    public function delete(DashboardRepository $dashRepo,$idDash ,EntityManagerInterface $em): Response
    {
        dump((int)$idDash);
        $dashboard =$dashRepo->findOneById((int)$idDash);
        dump($dashboard);
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
     * @return Response
     */
    public function show($idDash,ColonneRepository $colRepo ,Request $request,DashboardRepository $dashRepo,EntityManagerInterface $em, CarreRepository $repoCase): Response
    {
        $colonnesRempli =null;


        $dash=$dashRepo->find($idDash);


        dump($request);


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

        return $this->render('Dashboard/showDashboard.html.twig', ['Dash'=>$dash,'structure'=>$colonnesRempli,'colonnes'=>$colonnes

        ]);
    }
    /**
     * @Route("/configDashboard/{idDash}", name="configDashboard")
     */
    public function config($idDash ,Request $request,EntityManagerInterface $em, DashboardRepository $repo): Response
    {
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
     */
    public function deleteLigne($ligneId,$idDash,LigneRepository $ligneRepo,DashboardRepository $dashRepo,ColonneRepository $colRepo,EntityManagerInterface $em): Response
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
}

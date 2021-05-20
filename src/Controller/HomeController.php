<?php

namespace App\Controller;

use App\Entity\PossederDroitDash;
use App\Repository\DashboardRepository;
use App\Repository\PossederDroitDashRepository;
use App\Repository\TravaillerSurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param DashboardRepository $dashRepo
     * @param PossederDroitDashRepository $posRepo
     * @return Response
     */
    public function index(DashboardRepository $dashRepo,TravaillerSurRepository $posRepo): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $dashboards= array();
        $userConnecte=$this->getUser();
        $dashboards=$userConnecte->getDashboard($posRepo);



        return $this->render('home/index.html.twig', [
            'dashboards' => $dashboards,
        ]);
    }

}

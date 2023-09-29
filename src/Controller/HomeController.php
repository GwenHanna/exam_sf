<?php

namespace App\Controller;

use App\Entity\ContractType;
use App\Entity\User;
use App\Repository\ContractTypeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/home', name: 'app_home')]
    public function index(ContractTypeRepository $contractType, UserRepository $user): Response
    {
        $contracts = $contractType->findAll();
        $employe = $user->findAll();
        return $this->render('home/index.html.twig', [
            'contracts' => $contracts,
            'employees'   => $employe
        ]);
    }
}

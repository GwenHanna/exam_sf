<?php

namespace App\Controller;

use App\Entity\ContractType;
use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Repository\ContractTypeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{

    public function __construct(

        private UserRepository $user
    ) {
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/home', name: 'app_home')]
    public function index(ContractTypeRepository $contractType,): Response
    {
        $contracts = $contractType->findAll();
        $employe = $this->user->findAll();
        return $this->render('home/index.html.twig', [
            'contracts' => $contracts,
            'employees'   => $employe
        ]);
    }

    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/home/{id}', name: 'app_item')]
    public function item(User $employe, Request $request): Response
    {
        $user = $this->getUser();

        return $this->render('home/item.html.twig', [
            'employe'   => $employe,
            'user'      => $user,
        ]);
    }
}

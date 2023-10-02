<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Task;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $task = new Task();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {


                $fomData = $form->getData();
                $tasksSelected = $fomData->getTasks();
                $passwordHash = $this->passwordHasher->hashPassword(
                    $user,
                    $fomData->getPassword()
                );
                $user
                    ->setRoles(["ROLE_USER"])
                    ->setDateCreated(new DateTime())
                    ->setPassword($passwordHash);

                foreach ($tasksSelected as $key => $selectedTask) {

                    $user->addTask($selectedTask);
                }


                $filename = $form->get('filename')->getData();
                if ($filename) {
                    $originalFilename = pathinfo($filename->getClientOriginalName(), PATHINFO_FILENAME);

                    $safeFilename = $this->slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $filename->guessExtension();

                    try {
                        $filename->move(
                            $this->getParameter('file_name_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    $media = new Media();
                    $media
                        ->setFilename($newFilename)
                        ->setUser($user->getId());

                    $user->addMedium($media);

                    $this->addFlash('success', "L'employée à bien été enregistrer !");
                    $entityManager->persist($user);
                    $entityManager->persist($media);

                    $entityManager->flush();
                    return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
                }
            } catch (Exception $e) {
                //throw $th;
            }
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}

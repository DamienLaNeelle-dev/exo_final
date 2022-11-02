<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserFormType;
use App\Entity\Possessions;

use App\Form\PossessionFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PossessionsRepository;
use App\Service\UserService;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UsersController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {

        $user = new Users();
        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // dd($user);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('users/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/users', name: 'app_users')]
    public function users(UsersRepository $repository, UserService $userService): Response
    {
        $users = $repository->findAll();
        // dd($users);

        foreach ($users as $user) {

            $age = $userService->calculateAge($user->getBirthDate());

            $user->setAge($age);
        }

        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getNom();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);
        $jsonContent = ($serializer->serialize($users, 'json'));

        $response = new Response;

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return new Response($jsonContent);
    }


    #[Route('/addPossession/{id}', name: 'add_possession')]
    public function addPossession(Request $request, EntityManagerInterface $manager): Response
    {

        $possession = new Possessions();
        $form = $this->createForm(PossessionFormType::class, $possession);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $possession = $form->getData();
            // $possession->setUser($user);
            // dd($possession);

            $manager->persist($possession);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('users/formPossession.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/user/delete/{id}', name: 'delete_user')]
    public function deleteUser(int $id, UsersRepository $usersRepository, EntityManagerInterface $em): Response
    {
        $user = $usersRepository->findOneById($id);
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_users');
    }


    #[Route('/details/{id}', name: 'app_details')]
    public function details(UsersRepository $usersRepository, PossessionsRepository $possessionsRepository, $id): Response
    {
        $user = $usersRepository->find($id);
        // dump($user->getPossessions()[0]);
        $possessions = $possessionsRepository->find($id);
        return $this->render('users/details.html.twig', [
            'user' => $user, 'possessions' => $user->getPossessions()
        ]);
    }
}

<?php

namespace App\Controller;

use App\Service\UserService;
use Doctrine\ORM\EntityManager;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PossessionsRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class UsersController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    #[Route('/users', name: 'app_users')]
    public function users(UsersRepository $repository): Response
    {
        $users = $repository->findAll();
        // dd($users);

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

    #[Route('/users', name: 'add_users')]
    public function register(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {

        $userRegistered = $request->getContent();


        try {
            $user = $serializer->deserialize($userRegistered, User::class, 'json');
            $em->persist($user);
            $em->flush();
            $response = $this->json($user, 201, []);
            $response->headers->set('Access-Control-Allow-Origin', '*');
            return $response;
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
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
        // dump($user);
        $possessions = $possessionsRepository->findAll($id);
        dump($possessions);
        return $this->render('users/details.html.twig', [
            'user' => $user, 'possessions' => $possessions
        ]);
    }

    // #[Route('/', name:'add_user')]
    // public function add_user(UsersRepository $usersRepository, Request $request, SerializerInterface $serializer, UserService $userService): Response
    // {
    //     $jsonRecu = $request->getContent();
    //     $user = $serializer->deserialize($jsonRecu, Users::class, 'json');

    //     $usersRepository->save($user, true);

    //     $encoder = new JsonEncoder();
    //     $defaultContext = [
    //         AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context){
    //             return $object->getNom();
    //         },
    //     ];

    //     $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

    //     $serializer = new Serializer([$normalizer], [$encoder]);

    //     $users = $usersRepository->findAll();
    //     foreach ($users as $user) {
    //         $age = $userService->calcul($user->getBirthDate());
    //         $user->setAge($age);
    //     }

    //     $serializedUsers = $serializer->serialize($users, 'json', []);

    //     return $this->json($serializedUsers, 201, [
    //         'Content-Type' => 'application/json',
    //         'Access-Controll-Allow-Origin' => '*'
    //     ]);

    // }


}

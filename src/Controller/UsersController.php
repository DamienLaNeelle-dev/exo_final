<?php

namespace App\Controller;

use App\Repository\PossessionsRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context){
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

    #[Route('/user/delete/{id}', name: 'delete_user')]
    public function deleteUser(int $id, UsersRepository $usersRepository, EntityManagerInterface $em): Response 
    {
        $user = $usersRepository->findOneById($id);
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_users');
    }

    // #[Route('/possessions', name: 'app_usersPossessions')]
    // public function index2(PossessionsRepository $possessionsRepository, UsersRepository $usersRepository): Response
    // {

    //     // $possessions = $possessionsRepository
    //     // ->find($id);
    //     // $users = $usersRepository
    //     // ->find($id);

    //     return $this->render('possessions/index.html.twig', [
    //         'controller_name' => 'UsersController'
    //     ]);
    // }

    #[Route('/details/{id}', name: 'app_details')]
    public function details(UsersRepository $usersRepository, $id): Response
    {
        $users = $usersRepository->find($id);
        // dd($users);
        return $this->render('users/details.html.twig', [
            'controller_name' => 'UsersController', 'users' => $users
        ]);
    }

    // #[Route('/possessions/{id}', name:'app_possessions')]
    // public function userPossessions(PossessionsRepository $possessionsRepository, UsersRepository $usersRepository, $id): Response
    // {
    //     $encoder = new JsonEncoder();
    //     $defaultContext = [
    //         AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context){
    //             return $object->getNom();
    //         },
    //     ];

    //     $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
    //     $serializer = new Serializer([$normalizer], [$encoder]);

    //     $possessions = $possessionsRepository->find($id);
    //     $jsonContent = $serializer->serialize($possessions, 'json');

    //     $response = new Response;

    //     $response->headers->set('Content-Type', 'application/json');
    //     $response->headers->set('Access-Control-Allow-Origin', '*');
    //     return new Response($jsonContent);
    // }

}

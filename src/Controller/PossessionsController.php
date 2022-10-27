<?php

namespace App\Controller;

use App\Repository\PossessionsRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PossessionsController extends AbstractController
{
    #[Route('/possessions', name: 'app_possessions')]
    public function index(): Response
    {
        return $this->render('possessions/index.html.twig', [
            'controller_name' => 'PossessionsController',
        ]);
    }

    #[Route('/apiPossessions', name:'possession_user')]
    public function possessions(PossessionsRepository $possessionsRepository): Response
    {
        $possessions = $possessionsRepository->findAll();
        // dd($possessions);

        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context){
                return $object->getNom();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);
        $jsonContent = ($serializer->serialize($possessions, 'json'));

        $response = new Response;

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return new Response($jsonContent);
    }
}

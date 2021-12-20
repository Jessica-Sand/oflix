<?php

namespace App\Controller;

use App\Entity\Character;
use App\Form\Type\CharacterType;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/character", name="character_")
 */
class CharacterController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(CharacterRepository $characterRepository): Response
    {
        return $this->render('character/index.html.twig', [
            'characters' => $characterRepository->findAll(),
        ]);
    }
}

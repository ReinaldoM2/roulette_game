<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerFormType;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayersController extends AbstractController
{

    private $playerRepository;
    private $em;

    public function __construct(PlayerRepository $playerRepository, EntityManagerInterface $em)
    {
        $this->playerRepository = $playerRepository;
        $this->em = $em;
    }

    #[Route('/players', name: 'players')]
    public function index(): Response
    {

        $players = $this->playerRepository->findAll();

        return $this->render('players/index.html.twig', [
            'players' => $players,
            'game_color' => 0
        ]);
    }

    #[Route('/players/create', name: 'create_player')]
    public function create(Request $request): Response
    {
        $id = '0';
        $player = new Player();
        $form = $this->createForm(PlayerFormType::class, $player);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $newPlayer = $form->getData();
            $this->em->persist($newPlayer);
            $this->em->flush();
            return $this->redirectToRoute('players');
        }

        return $this->render('players/create.html.twig',[
            'player' => $id,
            'form' => $form->createView()
        ]);
    }
    #[Route('/players/edit/{id}', name: 'edit_player')]
    public function edit($id, Request $request):Response
    {
        $player = $this->playerRepository->find($id);
        $form = $this->createForm(PlayerFormType::class, $player);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $player->setName($form->get('name')->getData());
            $player->setLastName($form->get('last_name')->getData());
            $player->setAge($form->get('age')->getData());
            $player->setEmail($form->get('email')->getData());
            $player->setBalance($form->get('balance')->getData());
            $this->em->flush();
            return $this->redirectToRoute('players');
        }

        return $this->render('players/create.html.twig',[
            'player' => $player,
            'form' => $form->createView()
        ]);
    }
    #[Route('/players/delete/{id}', methods: ['GET', 'DELETE'], name: 'delete_plater')]
    public function delete($id): Response
    {
        $player = $this->playerRepository->find($id);
        $this->em->remove($player);
        $this->em->flush();
        return $this->redirectToRoute('players');
    }
}

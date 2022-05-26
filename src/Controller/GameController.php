<?php

namespace App\Controller;

use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{

    private $playerRepository;
    private $em;

    public function __construct(PlayerRepository $playerRepository, EntityManagerInterface $em)
    {
        $this->playerRepository = $playerRepository;
        $this->em = $em;
    }

    #[Route('/game', name: 'app_game')]
    public function index(): Response
    {
        $players = $this->playerRepository->findAll();
        $valiable_players = [];
        foreach ($players as $key => $player) {
            if ($player->getBalance() > 0) {
                array_push($valiable_players,$player);
            }
        }
        return $this->render('game/index.html.twig', [
            'players' => $valiable_players
        ]);
    }

    #[Route('/game/start', name: 'app_game_start')]
    public function gamestart(): Response
    {
        $players = $this->playerRepository->findAll();
        $valiable_players = [];
        $result_color_bet = [];
        $result_number_bet = [];
        $players_result    = [];
        $players_bet       = [];
        $bet_color  = 0;
        $bet_number = 0;
        $bet_player = 0;
        $game_color = rand(1,3);
        $game_number = intval(str_pad(rand(0,36), 2, "0", STR_PAD_LEFT));
        foreach ($players as $key => $player) {
            if ($player->getBalance() > 0) {
                array_push($valiable_players,$player);
            }
        }
        foreach ($valiable_players as $key => $player) {
            $balance = $player->getBalance();
            if ($balance <= 1000) {
                $bet_player = $balance;
            }else{
                $bet_player = rand(1,$balance);
            }
            $bet_color  = rand(1,3);
            array_push($result_color_bet,$bet_color);
            array_push($players_bet,$bet_player);
        }
        foreach ($valiable_players as $key => $player) {
            if ($result_color_bet[$key] === 3) {
                $bet_number = intval(str_pad(rand(0,1), 2, "0", STR_PAD_LEFT));
            }else{
                $bet_number = rand(1,36);
            }
            array_push($result_number_bet,$bet_number);
        }
        foreach ($valiable_players as $key => $player) {
            $new_balance = 0;
            if ($game_color === 3 && $result_color_bet[$key] === 3) {
                if ($game_number === intval($result_number_bet[$key])) {
                    $new_balance = $players_bet[$key] * 15;
                }
            }else{
                if ($game_number === intval($result_number_bet[$key])) {
                    $new_balance = $players_bet[$key] * 2;
                }
            }
            array_push($players_result,$new_balance);
        }
        foreach ($valiable_players as $key => $player) {
            $player->setBalance($players_result[$key]);
            $this->em->flush();
        }
        return $this->render('players/index.html.twig', [
            'players' => $valiable_players,
            'game_number' => $game_number,
            'game_color' => $game_color,
            'players_color' => $result_color_bet,
            'players_number' => $result_number_bet
        ]);
    }
}

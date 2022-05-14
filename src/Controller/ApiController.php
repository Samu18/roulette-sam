<?php

namespace App\Controller;

use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * Method to start the game
     * @Route("/", name="roulette_game")
     * @param Request $request
     * @return Response
     */
    public function game(Request $request): Response
    {
        return $this->render('roulette_game/index.html.twig');
    }


    /**
     * Method to get the players
     * @Route("/load-players", name="roulette_load_players")
     * @param Request $request
     * @return JsonResponse
     */
    public function loadPlayers(Request $request, PlayerRepository $playerRepository): JsonResponse
    {
        $players = $playerRepository->findActivePlayers();

        return new JsonResponse($players);
    }

    /**
     * Method to execute a roulette spin
     * @Route("/next-spin", name="roulette_next_spin")
     * @param Request $request
     * @return JsonResponse
     */
    public function nextSpin(Request $request, PlayerRepository $playerRepository): JsonResponse
    {
        $dataBets = $request->get('bets');
        $randomNumber = rand(0, 36);
        $betFactor = 1;
        $resultColor = '';
        if($randomNumber == 0){
            $betFactor = 9;
            $resultColor = 'green';
        }elseif ($randomNumber % 2 == 0){
            $resultColor = 'red';
        }else{
            $resultColor = 'black';
        }
        $totalBets = count($dataBets);
        for($i = 0; $i < $totalBets; $i++){
            if($dataBets[$i]['color'] === $resultColor){
                $dataBets[$i]['amount'] = intval($dataBets[$i]['amount']) * $betFactor;
            }else{
                $dataBets[$i]['amount'] = intval($dataBets[$i]['amount']) * -1;
            }
            $playerRepository->updateAmount($dataBets[$i]);
        }
        return new JsonResponse([ 'result_color' => $resultColor, 'result_bets' => $dataBets]);
    }
}

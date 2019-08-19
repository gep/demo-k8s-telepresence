<?php

namespace App\Controller;

use App\Entity\Good;
use App\Repository\GoodRepository;
use App\Services\Goods\GoodsService;
use ProbablyRational\RandomNameGenerator\All;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class GoodsController extends AbstractController
{

    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        return new Response('Goods store! Seems to be working. :)');
    }

    /**
     * @Route("/goods", name="goods")
     */
    public function getGoods(): JsonResponse
    {
        /** @var GoodRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Good::class);

        $goods = array_map(function($good) {
            $good['name'] = $good['name'] . '_custom';
            return $good;
        }, $repository->getGoods());

        $response = $this->json([
            'goods' => $goods,
            'path' => 'src/Controller/GoodsController.php',
        ]);

        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route("/phpinfo", name="phpinfo")
     */
    public function getPhpInfo(): Response
    {
        phpinfo();
        return new Response('Php info');
    }

    /**
     * @Route("/goods/create/{amount}", name="create_goods", requirements={"amount"="\d+"}, methods={"PUT","POST","OPTIONS"})
     * @param int $amount
     * @param GoodsService $goodsService
     * @param Request $request
     * @return JsonResponse
     */
    public function createGoods(int $amount, GoodsService $goodsService, Request $request): JsonResponse
    {
        if ($request->getMethod() == 'OPTIONS') {
            $response = $this->json(['message' => 'Options response']);
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'PUT,POST');
            return $response;
        }

        $goods = $goodsService->createGoods($amount);

        $response = $this->json(
            [
                'message' => sprintf("%d goods created", $amount),
                'goods' => $goods
            ]
        );

        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }




}

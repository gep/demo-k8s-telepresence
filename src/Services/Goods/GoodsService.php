<?php

namespace App\Services\Goods;

use App\Entity\Good;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use ProbablyRational\RandomNameGenerator\All;

class GoodsService
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createGoods(int $amount): iterable
    {
        $namesGenerator = All::create();

        $products = [];

        foreach (range(1, $amount) as $itemNumber) {
            $product = new Good();
            $product->setName($namesGenerator->getName());
            $product->setPrice((float)(rand(0, 200) + (rand(0, 100) / 100)));

            $this->em->persist($product);

            array_push($products, $product);
        }

        // actually executes the queries (i.e. the INSERT query)
        $this->em->flush();

        return $products;
    }
}
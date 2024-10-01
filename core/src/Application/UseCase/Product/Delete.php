<?php

namespace TechChallenge\Application\UseCase\Product;

use TechChallenge\Domain\Product\DAO\IProduct as IProductDAO;
use TechChallenge\Domain\Product\Repository\IProduct as IProductRepository;
use TechChallenge\Domain\Product\Exceptions\ProductNotFoundException;
use TechChallenge\Domain\Product\Entities\Product;

final class Delete
{
    public function __construct(private readonly IProductRepository $ProductRepository, private readonly IProductDAO $ProductDAO) {}

    public function execute(string $id): Product
    {
        if (!$this->ProductDAO->exist(["id" => $id]))
            throw new ProductNotFoundException();

        $product = $this->ProductRepository->show(["id" => $id]);

        $product->delete();

        return $product;
    }
}

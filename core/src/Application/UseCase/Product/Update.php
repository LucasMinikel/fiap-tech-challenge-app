<?php

namespace TechChallenge\Application\UseCase\Product;

use TechChallenge\Domain\Category\Exceptions\CategoryNotFoundException;
use TechChallenge\Domain\Category\DAO\ICategory as ICategoryDAO;
use TechChallenge\Domain\Product\DAO\IProduct as IProductDAO;
use TechChallenge\Domain\Product\SimpleFactory\Product as FactorySimpleProduct;
use TechChallenge\Application\DTO\Product\DtoInput;
use TechChallenge\Domain\Product\Exceptions\ProductNotFoundException;
use DateTime;
use TechChallenge\Domain\Product\Entities\Product;

final class Update
{
    public function __construct(
        private readonly IProductDAO $ProductDAO,
        private readonly ICategoryDAO $CategoryDAO
    ) {}

    public function execute(DtoInput $data): Product
    {
        if (!$this->ProductDAO->exist(["id" => $data->id]))
            throw new ProductNotFoundException();

        $productFactory = (new FactorySimpleProduct())
            ->new($data->id, $data->createdAt, $data->updatedAt)
            ->withNameDescriptionPriceImage($data->name, $data->description, $data->price, $data->image);

        if (!empty($data->categoryId)) {
            if (!$this->CategoryDAO->exist(["id" => $data->categoryId]))
                throw new CategoryNotFoundException();
            $productFactory->withCategoryId($data->categoryId);
        }

        $product = $productFactory->build();

        $product->setUpdatedAt(new DateTime());

        return $product;
    }
}

<?php

namespace TechChallenge\Application\UseCase\Product;

use TechChallenge\Domain\Category\Exceptions\CategoryNotFoundException;
use TechChallenge\Domain\Product\SimpleFactory\Product as FactorySimpleProduct;
use TechChallenge\Domain\Category\DAO\ICategory as ICategoryDAO;
use TechChallenge\Application\DTO\Product\DtoInput;
use TechChallenge\Domain\Product\Entities\Product;

final class Store
{
    public function __construct(private readonly ICategoryDAO $CategoryDAO) {}

    public function execute(DtoInput $data): Product
    {
        $productFactory = (new FactorySimpleProduct())
            ->new()
            ->withNameDescriptionPriceImage($data->name, $data->description, $data->price, $data->image);

        if (!empty($data->categoryId)) {
            if (!$this->CategoryDAO->exist(["id" => $data->categoryId]))
                throw new CategoryNotFoundException();
            $productFactory->withCategoryId($data->categoryId);
        }

        return $productFactory->build();
    }
}

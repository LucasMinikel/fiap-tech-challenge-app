<?php

namespace TechChallenge\Application\UseCase\Category;

use TechChallenge\Domain\Category\SimpleFactory\Category as SimpleFactoryCategory;
use TechChallenge\Application\DTO\Category\DtoInput;
use TechChallenge\Domain\Category\Entities\Category;

final class Store
{
    public function execute(DtoInput $dto): Category
    {
        $category = (new SimpleFactoryCategory())
            ->new()
            ->withNameType($dto->name, $dto->type)
            ->build();

        return $category;
    }
}

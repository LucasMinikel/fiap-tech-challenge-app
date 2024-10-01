<?php

namespace TechChallenge\Application\UseCase\Category;

use TechChallenge\Domain\Category\DAO\ICategory as ICategoryDAO;
use TechChallenge\Domain\Category\Exceptions\CategoryNotFoundException;
use TechChallenge\Domain\Category\SimpleFactory\Category as SimpleFactoryCategory;
use TechChallenge\Application\DTO\Category\DtoInput;
use DateTime;
use TechChallenge\Domain\Category\Entities\Category;

final class Update
{
    public function __construct(private readonly ICategoryDAO $CategoryDAO) {}

    public function execute(DtoInput $dto): Category
    {
        if (!$this->CategoryDAO->exist(["id" => $dto->id]))
            throw new CategoryNotFoundException();

        $category = (new SimpleFactoryCategory())
            ->new($dto->id, $dto->createdAt, $dto->updatedAt)
            ->withNameType($dto->name, $dto->type)
            ->build();

        $category->setUpdatedAt(new DateTime());

        return $category;
    }
}

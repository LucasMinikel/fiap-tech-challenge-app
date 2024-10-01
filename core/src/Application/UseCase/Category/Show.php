<?php

namespace TechChallenge\Application\UseCase\Category;

use TechChallenge\Domain\Category\Repository\ICategory as ICategoryRepository;
use TechChallenge\Domain\Category\DAO\ICategory as ICategoryDAO;
use TechChallenge\Domain\Category\Exceptions\CategoryNotFoundException;
use TechChallenge\Domain\Category\Entities\Category;

final class Show
{
    public function __construct(private readonly ICategoryRepository $CategoryRepository, private readonly ICategoryDAO $CategoryDAO) {}

    public function execute(string $id): Category
    {
        if (!$this->CategoryDAO->exist(["id" => $id]))
            throw new CategoryNotFoundException();

        return $this->CategoryRepository->show(["id" => $id], true);
    }
}

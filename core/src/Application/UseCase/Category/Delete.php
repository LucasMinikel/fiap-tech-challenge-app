<?php

namespace TechChallenge\Application\UseCase\Category;

use TechChallenge\Domain\Category\Repository\ICategory as ICategoryRepository;
use TechChallenge\Domain\Category\DAO\ICategory as ICategoryDAO;
use TechChallenge\Domain\Category\Entities\Category;
use TechChallenge\Domain\Category\Exceptions\CategoryNotFoundException;

final class Delete
{
    public function __construct(private readonly ICategoryRepository $CategoryRepository, private readonly ICategoryDAO $CategoryDAO) {}

    public function execute(string $id): Category
    {
        if (!$this->CategoryDAO->exist(["id" => $id]))
            throw new CategoryNotFoundException();

        $category = $this->CategoryRepository->show(["id" => $id], true);

        $category->delete();

        return $category;
    }
}

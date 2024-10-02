<?php

namespace TechChallenge\Application\UseCase\Category;

use TechChallenge\Domain\Category\Repository\ICategory as ICategoryRepository;

final class Index
{
    public function __construct(private readonly ICategoryRepository $CategoryRepository) {}

    public function execute(array $filters = [], array|bool $append = []): array
    {
        return $this->CategoryRepository->index($filters, $append);
    }
}

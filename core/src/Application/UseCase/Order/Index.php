<?php

namespace TechChallenge\Application\UseCase\Order;

use TechChallenge\Domain\Order\Repository\IOrder as IOrderRepository;

final class Index
{
    public function __construct(private readonly IOrderRepository $OrderRepository) {}

    public function execute(array $filters = [], array|bool $append = []): array
    {
        return $this->OrderRepository->index($filters, $append);
    }
}

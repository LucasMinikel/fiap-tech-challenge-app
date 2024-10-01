<?php

namespace TechChallenge\Adapters\Controllers\Order;

use TechChallenge\Domain\Shared\AbstractFactory\Repository as AbstractFactoryRepository;
use TechChallenge\Application\UseCase\Order\Delete as UseCaseOrderDelete;

final class Delete
{
    public function __construct(private readonly AbstractFactoryRepository $AbstractFactoryRepository) {}

    public function execute(string $id): void
    {
        $OrderRepository = $this->AbstractFactoryRepository->createOrderRepository();

        $order = (new UseCaseOrderDelete(
            $OrderRepository,
            $this->AbstractFactoryRepository->getDAO()->createOrderDAO()
        ))
            ->execute($id);

        $OrderRepository->delete($order);
    }
}

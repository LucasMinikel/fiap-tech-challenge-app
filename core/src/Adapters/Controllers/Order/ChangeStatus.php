<?php

namespace TechChallenge\Adapters\Controllers\Order;

use TechChallenge\Domain\Shared\AbstractFactory\Repository as AbstractFactoryRepository;
use TechChallenge\Application\UseCase\Order\ChangeStatus as UseCaseOrderChangeStatus;

final class ChangeStatus
{
    public function __construct(private readonly AbstractFactoryRepository $AbstractFactoryRepository) {}

    public function execute(?string $id, ?string $status): void
    {
        $OrderRepository = $this->AbstractFactoryRepository->createOrderRepository();

        $order = (new UseCaseOrderChangeStatus(
            $OrderRepository,
            $this->AbstractFactoryRepository->getDAO()->createOrderDAO()
        ))
            ->execute($id, $status);

        $OrderRepository->update($order);
    }
}

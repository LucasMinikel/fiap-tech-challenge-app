<?php

namespace TechChallenge\Adapters\Controllers\Order;

use TechChallenge\Domain\Shared\AbstractFactory\Repository as AbstractFactoryRepository;
use TechChallenge\Application\UseCase\Order\Checkout as UseCaseOrderCheckout;

final class Checkout
{
    public function __construct(private readonly AbstractFactoryRepository $AbstractFactoryRepository) {}

    public function execute(?string $id): void
    {
        $OrderRepository = $this->AbstractFactoryRepository->createOrderRepository();

        $order = (new UseCaseOrderCheckout(
            $OrderRepository,
            $this->AbstractFactoryRepository->getDAO()->createOrderDAO()
        ))
            ->execute($id);

        $OrderRepository->update($order);
    }
}

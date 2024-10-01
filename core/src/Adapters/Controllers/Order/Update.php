<?php

namespace TechChallenge\Adapters\Controllers\Order;

use TechChallenge\Domain\Shared\AbstractFactory\Repository as AbstractFactoryRepository;
use TechChallenge\Application\DTO\Order\DtoInput as OrderDTOInput;
use TechChallenge\Application\UseCase\Order\Update as UseCaseOrderUpdate;

final class Update
{
    public function __construct(private readonly AbstractFactoryRepository $AbstractFactoryRepository) {}

    public function execute(OrderDTOInput $dto): void
    {
        $OrderRepository = $this->AbstractFactoryRepository->createOrderRepository();

        $order = (new UseCaseOrderUpdate(
            $OrderRepository,
            $this->AbstractFactoryRepository->createProductRepository(),
            $this->AbstractFactoryRepository->getDAO()->createOrderDAO(),
            $this->AbstractFactoryRepository->getDAO()->createCustomerDAO(),
            $this->AbstractFactoryRepository->getDAO()->createProductDAO()
        ))
            ->execute($dto);

        $OrderRepository->update($order);
    }
}

<?php

namespace TechChallenge\Application\UseCase\Order;

use TechChallenge\Domain\Order\DAO\IOrder as IOrderDAO;
use TechChallenge\Domain\Order\Repository\IOrder as IOrderRepository;
use TechChallenge\Domain\Order\Entities\Order as OrderEntity;
use TechChallenge\Domain\Order\Exceptions\OrderNotFoundException;

final class Show
{
    public function __construct(private readonly IOrderRepository $OrderRepository, private readonly IOrderDAO $OrderDAO) {}

    public function execute(?string $id): OrderEntity
    {
        if (!$id || !$this->OrderDAO->exist(["id" => $id]))
            throw new OrderNotFoundException();

        return $this->OrderRepository->show(["id" => $id], true);
    }
}

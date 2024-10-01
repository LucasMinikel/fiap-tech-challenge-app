<?php

namespace TechChallenge\Application\UseCase\Order;

use TechChallenge\Domain\Order\Repository\IOrder as IOrderRepository;
use TechChallenge\Domain\Order\DAO\IOrder as IOrderDAO;
use TechChallenge\Domain\Order\Exceptions\OrderNotFoundException;
use TechChallenge\Domain\Order\Entities\Order;

final class Webhook
{
    public function __construct(private readonly IOrderRepository $OrderRepository, private readonly IOrderDAO $OrderDAO) {}

    public function execute(?string $id): Order
    {
        if (!$id || !$this->OrderDAO->exist(["id" => $id]))
            throw new OrderNotFoundException();

        $order = $this->OrderRepository->show(["id" => $id], true);

        $order->setAsPaid();

        return $order;
    }
}

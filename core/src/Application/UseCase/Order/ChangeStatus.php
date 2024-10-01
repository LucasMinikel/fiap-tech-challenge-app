<?php

namespace TechChallenge\Application\UseCase\Order;

use TechChallenge\Domain\Order\Enum\OrderStatus;
use TechChallenge\Domain\Order\Exceptions\InvalidStatusOrder;
use TechChallenge\Domain\Order\Exceptions\OrderNotFoundException;
use TechChallenge\Domain\Order\Repository\IOrder as IOrderRepository;
use TechChallenge\Domain\Order\DAO\IOrder as IOrderDAO;
use ValueError;
use TechChallenge\Domain\Order\Entities\Order;

final class ChangeStatus
{
    public function __construct(private readonly IOrderRepository $OrderRepository, private readonly IOrderDAO $OrderDAO) {}

    public function execute(?string $id, ?string $status): Order
    {
        if (!$id || !$this->OrderDAO->exist(["id" => $id]))
            throw new OrderNotFoundException();

        $order = $this->OrderRepository->show(["id" => $id], true);

        try {
            $status = OrderStatus::from($status);
        } catch (ValueError $error) {
            throw new InvalidStatusOrder();
        }

        $order->setStatusByStatus($status);

        return $order;
    }
}

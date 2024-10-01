<?php

namespace TechChallenge\Domain\Order\SimpleFactory;

use DateTime;
use TechChallenge\Domain\Customer\Entities\Customer;
use TechChallenge\Domain\Order\Enum\OrderStatus;
use TechChallenge\Domain\Order\Entities\Order as OrderEntity;
use TechChallenge\Domain\Shared\ValueObjects\Price;

class Order
{
    private ?OrderEntity $order = null;

    public function new(
        ?string $id = null,
        float $total = 0,
        String|DateTime $createdAt = null,
        String|DateTime $updatedAt = null
    ): self {
        if (!is_null($createdAt))
            $createdAt = is_string($createdAt) ? new DateTime($createdAt) : $createdAt;

        if (!is_null($updatedAt))
            $updatedAt = is_string($updatedAt) ? new DateTime($updatedAt) : $updatedAt;

        $this->order = OrderEntity::create($id, $createdAt, $updatedAt);

        $this->order->setTotal(new Price($total));

        return $this;
    }

    public function restore(
        string $id,
        string $status,
        float $total = 0,
        String|DateTime $createdAt = null,
        String|DateTime $updatedAt = null
    ): self {
        if (!is_null($createdAt))
            $createdAt = is_string($createdAt) ? new DateTime($createdAt) : $createdAt;

        if (!is_null($updatedAt))
            $updatedAt = is_string($updatedAt) ? new DateTime($updatedAt) : $updatedAt;

        $this->order = OrderEntity::restore($id, OrderStatus::from($status), $createdAt, $updatedAt);

        $this->order->setTotal(new Price($total));

        return $this;
    }

    public function withCustomerId(?string $customerId): self
    {
        $this->order->setCustomerId($customerId);

        return $this;
    }

    public function withCustomer(Customer $customer): self
    {
        $this->order->setCustomer($customer);

        return $this;
    }

    public function withCustomerIdCustomer(string $customerId, Customer $customer): self
    {
        $this->order
            ->setCustomerId($customerId)
            ->setCustomer($customer);

        return $this;
    }

    public function withItems(array $items): self
    {
        $this->order->setItems($items);

        return $this;
    }

    public function withStatusHistories(array $statusHistories): self
    {
        $this->order->setStatusHistories($statusHistories);

        return $this;
    }

    public function build(): OrderEntity
    {
        return $this->order;
    }
}

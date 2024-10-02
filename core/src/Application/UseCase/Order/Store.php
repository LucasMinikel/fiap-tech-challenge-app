<?php

namespace TechChallenge\Application\UseCase\Order;

use TechChallenge\Domain\Order\SimpleFactory\Order as FactorySimpleOrder;
use TechChallenge\Domain\Order\SimpleFactory\Item as FactorySimpleOrderItem;
use TechChallenge\Application\DTO\Order\DtoInput;
use TechChallenge\Domain\Customer\DAO\ICustomer as ICustomerDAO;
use TechChallenge\Domain\Product\DAO\IProduct as IProductDAO;
use TechChallenge\Domain\Product\Repository\IProduct as IProductRepository;
use TechChallenge\Domain\Customer\Exceptions\CustomerNotFoundException;
use TechChallenge\Domain\Product\Exceptions\ProductNotFoundException;
use TechChallenge\Domain\Order\Entities\Order;

final class Store
{
    public function __construct(
        private readonly IProductRepository $ProductRepository,
        private readonly IProductDAO $ProductDAO,
        private readonly ICustomerDAO $CustomerDAO
    ) {}

    public function execute(DtoInput $dto): Order
    {
        $order = (new FactorySimpleOrder())
            ->new()
            ->build();

        if ($dto->customerId) {
            if (!$this->CustomerDAO->exist(["id" => $dto->customerId]))
                throw new CustomerNotFoundException();

            $order->setCustomerId($dto->customerId);
        }

        $FactorySimpleOrderItem = new FactorySimpleOrderItem();

        foreach ($dto->items as $item) {

            if (!$item->productId || !$this->ProductDAO->exist(["id" => $item->productId]))
                throw new ProductNotFoundException();

            $product = $this->ProductRepository->show(["id" => $item->productId]);

            $item = $FactorySimpleOrderItem
                ->new(null, $product->getId(), $order->getId())
                ->withQuantityPrice($item->quantity, $product->getPrice()->getValue())
                ->build();

            $order->setItem($item);
        }

        $order->calcTotal();

        $order->setAsNew();

        return $order;
    }
}

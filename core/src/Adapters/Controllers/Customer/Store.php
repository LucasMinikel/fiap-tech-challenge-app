<?php

namespace TechChallenge\Adapters\Controllers\Customer;

use TechChallenge\Domain\Shared\AbstractFactory\Repository as AbstractFactoryRepository;
use TechChallenge\Application\DTO\Customer\DtoInput as CustomerDTOInput;
use TechChallenge\Application\UseCase\Customer\Store as UseCaseCustomerStore;

final class Store
{
    public function __construct(private readonly AbstractFactoryRepository $AbstractFactoryRepository) {}

    public function execute(CustomerDTOInput $dto): string
    {
        $customer = (new UseCaseCustomerStore($this->AbstractFactoryRepository->getDAO()->createCustomerDAO()))->execute($dto);

        $this->AbstractFactoryRepository->createCustomerRepository()->store($customer);

        return $customer->getId();
    }
}
